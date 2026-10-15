<?php

namespace App\Controllers;

use App\Models\ProductoModel;

class CatalogController extends ShopController
{
    public function index()
    {
        $products = (new ProductoModel())->where('disponible', 1)->orderBy('id_producto', 'DESC')->findAll();
        return $this->render('shop/catalog', ['title' => 'Colección', 'products' => $products]);
    }

    public function admin()
    {
        if ($r = $this->guardAdmin()) return $r;
        return $this->render('admin/products', [
            'title' => 'Administrar productos',
            'products' => (new ProductoModel())->orderBy('id_producto', 'DESC')->findAll(),
        ]);
    }

    private function fields(): array
    {
        return [
            'nombre' => trim((string)$this->request->getPost('nombre')),
            'descripcion' => trim((string)$this->request->getPost('descripcion')),
            'categoria' => trim((string)$this->request->getPost('categoria')),
            'precio' => $this->request->getPost('precio'),
            'stock' => $this->request->getPost('stock'),
            'disponible' => $this->request->getPost('disponible') === '1' ? 1 : 0,
        ];
    }

    private function validFields(array $f): bool
    {
        return $f['nombre'] !== '' && mb_strlen($f['nombre']) <= 150
            && mb_strlen($f['categoria']) <= 100 && $f['categoria'] !== ''
            && is_numeric($f['precio']) && (float)$f['precio'] > 0
            && (float)$f['precio'] <= 99999999.99
            && filter_var($f['stock'], FILTER_VALIDATE_INT) !== false
            && (int)$f['stock'] >= 0;
    }

    private function photo(?string $previous = null): ?string
    {
        $file = $this->request->getFile('imagen');
        if (! $file || $file->getError() === UPLOAD_ERR_NO_FILE) return $previous;
        if (! $file->isValid() || $file->getSize() > 2 * 1024 * 1024
            || ! in_array($file->getMimeType(), ['image/png', 'image/jpeg', 'image/webp'], true)) {
            throw new \RuntimeException('La imagen debe ser JPG, PNG o WEBP y no superar 2 MB.');
        }
        $file->move(FCPATH . 'img', $file->getRandomName());
        return $file->getName();
    }

    public function store()
    {
        if ($r = $this->guardAdmin()) return $r;
        $f = $this->fields();
        if (! $this->validFields($f)) return redirect()->back()->withInput()->with('error', 'Revisa nombre, categoría, precio y stock.');
        try {
            $f['imagen'] = $this->photo();
            (new ProductoModel())->insert($f);
        } catch (\Throwable $e) {
            log_message('error', 'Alta producto: '.$e->getMessage());
            return redirect()->back()->with('error', 'No se pudo guardar: verifica los datos y la imagen.');
        }
        return redirect()->to(site_url('admin/productos'))->with('success', 'Producto registrado.');
    }

    public function edit(int $id)
    {
        if ($r = $this->guardAdmin()) return $r;
        $product = (new ProductoModel())->find($id);
        if (! $product) throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        return $this->render('admin/edit_product', ['title' => 'Editar producto', 'product' => $product]);
    }

    public function update(int $id)
    {
        if ($r = $this->guardAdmin()) return $r;
        $model = new ProductoModel();
        $product = $model->find($id);
        if (! $product) throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        $f = $this->fields();
        if (! $this->validFields($f)) return redirect()->back()->withInput()->with('error', 'Revisa los datos del producto.');
        try {
            $f['imagen'] = $this->photo($product['imagen']);
            $model->update($id, $f);
        } catch (\Throwable $e) {
            log_message('error', 'Editar producto: '.$e->getMessage());
            return redirect()->back()->with('error', 'No se pudo actualizar el producto.');
        }
        return redirect()->to(site_url('admin/productos'))->with('success', 'Producto actualizado.');
    }

    public function remove(int $id)
    {
        if ($r = $this->guardAdmin()) return $r;
        $model = new ProductoModel();
        if (! $model->find($id)) throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        // Baja lógica para conservar las referencias e historial de pedidos.
        $model->update($id, ['disponible' => 0]);
        return redirect()->to(site_url('admin/productos'))->with('success', 'Producto retirado del catálogo (historial conservado).');
    }
}
