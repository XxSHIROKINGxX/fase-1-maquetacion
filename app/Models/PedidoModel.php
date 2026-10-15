<?php
namespace App\Models;
use CodeIgniter\Model;
class PedidoModel extends Model
{
    protected $table = 'pedidos';
    protected $primaryKey = 'id_pedido';
    protected $returnType = 'array';
    protected $allowedFields = [
        'id_usuario', 'total', 'estado', 'telefono', 'provincia', 'ciudad',
        'direccion', 'referencia', 'metodo_entrega', 'metodo_pago',
        'empresa_envio', 'comprobante_pago', 'estado_pago', 'fecha_comprobante',
        'fecha_envio', 'codigo_seguimiento',
    ];
    protected $useTimestamps = false;
}
