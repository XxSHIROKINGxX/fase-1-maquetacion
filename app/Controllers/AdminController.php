<?php

namespace App\Controllers;

use App\Models\PedidoModel;
use App\Models\UsuarioModel;
use App\Models\ProductoModel;

class AdminController extends ShopController
{
    public function index()
    {
        if ($r = $this->guardAdmin()) return $r;
        $db = db_connect();
        return $this->render('admin/dashboard', ['title' => 'Panel administrativo', 'stats' => [
            'users' => $db->table('usuarios')->countAllResults(),
            'products' => $db->table('productos')->countAllResults(),
            'orders' => $db->table('pedidos')->countAllResults(),
            'pending' => $db->table('pedidos')->where('estado', 'pendiente')->countAllResults(),
        ]]);
    }

    public function orders()
    {
        if ($r = $this->guardAdmin()) return $r;
        $orders = db_connect()->table('pedidos p')
            ->select('p.*, u.nombre, u.apellido, u.email')
            ->join('usuarios u','u.id_usuario=p.id_usuario')
            ->orderBy('p.id_pedido','DESC')->get()->getResultArray();
        return $this->render('admin/orders', ['title'=>'Administrar pedidos', 'orders'=>$orders]);
    }

    public function order(int $id)
    {
        if ($r = $this->guardAdmin()) return $r;
        $order = db_connect()->table('pedidos p')->select('p.*, u.nombre, u.apellido, u.email')
            ->join('usuarios u','u.id_usuario=p.id_usuario')->where('p.id_pedido',$id)->get()->getRowArray();
        if (! $order) throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        $details = db_connect()->table('detalle_pedido d')->select('d.*,p.nombre')
            ->join('productos p','p.id_producto=d.id_producto')->where('d.id_pedido',$id)->get()->getResultArray();
        return $this->render('admin/order_detail', ['title'=>'Administrar pedido #'.$id, 'order'=>$order, 'details'=>$details]);
    }

    public function setStatus(int $id)
    {
        if ($r = $this->guardAdmin()) return $r;
        $status = (string)$this->request->getPost('estado');
        $allowed = ['pendiente','procesando','enviado','entregado','cancelado'];
        if (! in_array($status, $allowed, true)) return redirect()->back()->with('error','Estado inválido.');
        $db = db_connect();
        $db->transBegin();
        try {
            $order = $db->query('SELECT * FROM pedidos WHERE id_pedido=? FOR UPDATE', [$id])->getRowArray();
            if (! $order) throw new \DomainException('Pedido inexistente.');
            if ($order['estado'] === 'cancelado' && $status !== 'cancelado') throw new \DomainException('No se puede reactivar un pedido cancelado.');
            if ($order['estado'] === 'entregado' && $status !== 'entregado') throw new \DomainException('No se puede modificar un pedido entregado.');
            if ($status !== 'cancelado' && in_array($status,['procesando','enviado','entregado'],true)
                && $order['metodo_pago'] === 'transferencia' && $order['estado_pago'] !== 'verificado') {
                throw new \DomainException('Verifica la transferencia antes de procesar este pedido.');
            }
            if ($status === 'cancelado' && $order['estado'] !== 'cancelado') {
                $details = $db->table('detalle_pedido')->where('id_pedido',$id)->get()->getResultArray();
                foreach ($details as $item) {
                    $db->table('productos')->where('id_producto',$item['id_producto'])
                        ->set('stock','stock + '.(int)$item['cantidad'],false)->update();
                }
            }
            $fields = ['estado'=>$status];
            if (in_array($status,['enviado','entregado'],true) && ! $order['codigo_seguimiento']) {
                $fields['codigo_seguimiento'] = 'ROSEA-'.str_pad((string)$id, 6,'0', STR_PAD_LEFT);
                $fields['fecha_envio'] = date('Y-m-d H:i:s');
            }
            $db->table('pedidos')->where('id_pedido',$id)->update($fields);
            if (! $db->transStatus()) throw new \RuntimeException('Error en base de datos');
            $db->transCommit();
            return redirect()->to(site_url('admin/pedidos/'.$id))->with('success','Estado actualizado.');
        } catch (\Throwable $e) {
            $db->transRollback();
            log_message('error','Cambiar estado: '.$e->getMessage());
            return redirect()->back()->with('error', $e instanceof \DomainException ? $e->getMessage() : 'No se pudo modificar el pedido.');
        }
    }

    public function setPayment(int $id)
    {
        if ($r = $this->guardAdmin()) return $r;
        $status = (string)$this->request->getPost('estado_pago');
        if (! in_array($status, ['pendiente','verificado','rechazado'], true)) {
            return redirect()->back()->with('error','Estado de pago inválido.');
        }
        $model = new PedidoModel();
        $order = $model->find($id);
        if (! $order) throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        if ($order['estado'] === 'cancelado') return redirect()->back()->with('error','Pedido cancelado.');
        if ($order['metodo_pago'] === 'transferencia' && $status === 'verificado' && ! $order['comprobante_pago']) {
            return redirect()->back()->with('error','Primero debe adjuntarse un comprobante.');
        }
        $model->update($id,['estado_pago'=>$status]);
        return redirect()->to(site_url('admin/pedidos/'.$id))->with('success','Pago actualizado.');
    }

    public function users()
    {
        if ($r = $this->guardAdmin()) return $r;
        $users = (new UsuarioModel())->select('id_usuario,nombre,apellido,email,rol,fecha_registro')
            ->orderBy('id_usuario','DESC')->findAll();
        return $this->render('admin/users',['title'=>'Usuarios', 'users'=>$users]);
    }

    public function setRole(int $id)
    {
        if ($r = $this->guardAdmin()) return $r;
        $role = (string)$this->request->getPost('rol');
        if (! in_array($role,['cliente','admin'],true)) return redirect()->back()->with('error','Rol inválido.');
        if ($id === (int)session('id_usuario') && $role !== 'admin') {
            return redirect()->back()->with('error','No puedes eliminar tu propio acceso de administrador.');
        }
        $model = new UsuarioModel();
        if (! $model->find($id)) throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        $model->update($id,['rol'=>$role]);
        return redirect()->to(site_url('admin/usuarios'))->with('success','Rol actualizado.');
    }
}
