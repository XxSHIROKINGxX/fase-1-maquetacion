<?php

namespace App\Controllers;

use App\Models\PedidoModel;
use App\Models\ProductoModel;
use App\Models\UsuarioModel;

class OrderController extends ShopController
{
    public function profile()
    {
        if ($r = $this->guardUser()) return $r;
        $user = (new UsuarioModel())->find((int) session('id_usuario'));
        if (! $user) { session()->destroy(); return redirect()->to(site_url('login')); }
        $orders = (new PedidoModel())->where('id_usuario', $user['id_usuario'])->orderBy('id_pedido','DESC')->findAll();
        return $this->render('orders/profile', ['title' => 'Mi perfil', 'user' => $user, 'orders' => $orders]);
    }

    public function checkout(int $id)
    {
        if ($r = $this->guardUser()) return $r;
        $product = (new ProductoModel())->find($id);
        if (! $product || (int)$product['disponible'] !== 1 || (int)$product['stock'] < 1) {
            return redirect()->to(site_url('productos'))->with('error', 'Producto no disponible.');
        }
        return $this->render('shop/checkout', ['title' => 'Finalizar compra', 'product' => $product]);
    }

    public function create()
    {
        if ($r = $this->guardUser()) return $r;
        $data = [
            'id_producto' => (int)$this->request->getPost('id_producto'),
            'cantidad' => (int)$this->request->getPost('cantidad'),
            'telefono' => trim((string)$this->request->getPost('telefono')),
            'provincia' => trim((string)$this->request->getPost('provincia')),
            'ciudad' => trim((string)$this->request->getPost('ciudad')),
            'direccion' => trim((string)$this->request->getPost('direccion')),
            'referencia' => trim((string)$this->request->getPost('referencia')),
            'metodo_entrega' => (string)$this->request->getPost('metodo_entrega'),
            'metodo_pago' => (string)$this->request->getPost('metodo_pago'),
        ];
        if ($data['id_producto'] < 1 || $data['cantidad'] < 1 || $data['cantidad'] > 50
            || ! preg_match('/^[0-9+()\-\s]{7,20}$/', $data['telefono'])
            || $data['provincia'] === '' || mb_strlen($data['provincia']) > 100
            || $data['ciudad'] === '' || mb_strlen($data['ciudad']) > 100
            || $data['direccion'] === '' || mb_strlen($data['direccion']) > 255
            || mb_strlen($data['referencia']) > 255
            || ! in_array($data['metodo_entrega'], ['delivery','retiro'], true)
            || ! in_array($data['metodo_pago'], ['efectivo','transferencia'], true)) {
            return redirect()->back()->withInput()->with('error', 'Completa correctamente la información de entrega y pago.');
        }

        // Usamos el servicio de base de datos de CodeIgniter y una transacción con bloqueo.
        $db = db_connect();
        $db->transBegin();
        try {
            $product = $db->query('SELECT id_producto, precio, stock, disponible FROM productos WHERE id_producto = ? FOR UPDATE', [$data['id_producto']])->getRowArray();
            if (! $product || (int)$product['disponible'] !== 1 || (int)$product['stock'] < $data['cantidad']) {
                throw new \DomainException('El producto ya no tiene stock suficiente.');
            }
            $price = (float)$product['precio'];
            $total = number_format($price * $data['cantidad'], 2, '.', '');
            $db->table('pedidos')->insert([
                'id_usuario' => (int)session('id_usuario'), 'total' => $total,
                'telefono' => $data['telefono'], 'provincia' => $data['provincia'],
                'ciudad' => $data['ciudad'], 'direccion' => $data['direccion'],
                'referencia' => $data['referencia'], 'metodo_entrega' => $data['metodo_entrega'],
                'metodo_pago' => $data['metodo_pago'], 'estado' => 'pendiente', 'estado_pago' => 'pendiente',
            ]);
            $orderId = (int)$db->insertID();
            $db->table('detalle_pedido')->insert([
                'id_pedido' => $orderId, 'id_producto' => $data['id_producto'],
                'cantidad' => $data['cantidad'], 'precio_unitario' => $price, 'subtotal' => $total,
            ]);
            $db->table('productos')->where('id_producto', $data['id_producto'])
                ->set('stock', 'stock - ' . $data['cantidad'], false)->update();
            if (! $db->transStatus()) throw new \RuntimeException('Transacción fallida');
            $db->transCommit();
            return redirect()->to(site_url('pedidos/'.$orderId))->with('success', '¡Tu pedido fue registrado!');
        } catch (\Throwable $e) {
            $db->transRollback();
            log_message('error', 'Crear pedido: '.$e->getMessage());
            return redirect()->back()->withInput()->with('error', $e instanceof \DomainException ? $e->getMessage() : 'Ocurrió un problema al crear el pedido.');
        }
    }

    private function ownedOrder(int $id): array
    {
        $order = (new PedidoModel())->find($id);
        if (! $order || ((int)$order['id_usuario'] !== (int)session('id_usuario') && session('rol') !== 'admin')) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
        return $order;
    }

    public function show(int $id)
    {
        if ($r = $this->guardUser()) return $r;
        $order = $this->ownedOrder($id);
        $details = db_connect()->table('detalle_pedido d')->select('d.*, p.nombre, p.imagen')
            ->join('productos p', 'p.id_producto = d.id_producto')->where('d.id_pedido', $id)->get()->getResultArray();
        return $this->render('orders/detail', ['title' => 'Pedido #'.$id, 'order'=>$order, 'details'=>$details]);
    }

    public function uploadReceipt(int $id)
    {
        if ($r = $this->guardUser()) return $r;
        $order = $this->ownedOrder($id);
        if ($order['metodo_pago'] !== 'transferencia' || $order['estado'] === 'cancelado') {
            return redirect()->back()->with('error','Este pedido no acepta comprobantes.');
        }
        $file = $this->request->getFile('comprobante');
        if (! $file || ! $file->isValid() || $file->getSize() > 3 * 1024 * 1024
            || ! in_array($file->getMimeType(), ['image/png','image/jpeg','application/pdf'], true)) {
            return redirect()->back()->with('error','Adjunta JPG, PNG o PDF de máximo 3 MB.');
        }
        $dir = WRITEPATH . 'comprobantes';
        if (! is_dir($dir) && ! mkdir($dir, 0750, true) && ! is_dir($dir)) {
            return redirect()->back()->with('error', 'No se puede guardar el comprobante.');
        }
        $name = $file->getRandomName();
        try {
            $file->move($dir, $name);
            (new PedidoModel())->update($id, [
                'comprobante_pago' => $name, 'estado_pago' => 'pendiente',
                'fecha_comprobante' => date('Y-m-d H:i:s'),
            ]);
        } catch (\Throwable $e) {
            log_message('error', 'Comprobante: '.$e->getMessage());
            return redirect()->back()->with('error', 'No se pudo almacenar el comprobante.');
        }
        return redirect()->to(site_url('pedidos/'.$id))->with('success','Comprobante enviado para revisión.');
    }

    public function receipt(int $id)
    {
        if ($r = $this->guardUser()) return $r;
        $order = $this->ownedOrder($id);
        $name = basename((string)($order['comprobante_pago'] ?? ''));
        $file = WRITEPATH . 'comprobantes/' . $name;
        if ($name === '' || ! is_file($file)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
        return $this->response->download($file, null);
    }
}
