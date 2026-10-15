<?php
namespace App\Models;
use CodeIgniter\Model;
class ProductoModel extends Model
{
    protected $table = 'productos';
    protected $primaryKey = 'id_producto';
    protected $returnType = 'array';
    protected $allowedFields = ['nombre', 'descripcion', 'precio', 'stock', 'categoria', 'imagen', 'disponible'];
    protected $useTimestamps = false;
}
