<?php
namespace App\Models;
use CodeIgniter\Model;

class NoticiaImagenModel extends Model
{
    protected $table = 'noticias_imagenes';
    protected $primaryKey = 'id';
    protected $allowedFields = ['noticia_id', 'imagen_path'];
    protected $useTimestamps = true;
}