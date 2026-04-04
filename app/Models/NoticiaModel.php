<?php

namespace App\Models;

use CodeIgniter\Model;

class NoticiaModel extends Model
{
    protected $table         = 'noticias';
    protected $primaryKey    = 'id';

    protected $allowedFields = [
        'titulo',
        'descripcion',
        'imagen',
        'fecha_creacion',
        'institucion_id',
        'slug'
    ];

    protected $useTimestamps = false;

    protected $beforeInsert  = ['generarSlug'];
    protected $beforeUpdate  = ['generarSlug'];

    protected function generarSlug(array $data)
    {
        if (isset($data['data']['titulo'])) {
            $baseSlug = url_title(iconv('UTF-8', 'ASCII//TRANSLIT', $data['data']['titulo']), '-', true);
            $slug     = $baseSlug;
            $contador = 1;

            while ($this->where('slug', $slug)->first()) {
                $slug = $baseSlug . '-' . $contador;
                $contador++;
            }

            $data['data']['slug'] = $slug;
        }

        return $data;
    }

    public function obtenerPorSlug($slug)
    {
        return $this->where('slug', esc($slug))->first();
    }
    public function obtener6()
    {
        return $this->where('institucion_id', "0")->orderBy('id', 'DESC')->limit(6)->findAll();
    }
    public function obtener3()
    {
        return $this->where('institucion_id', "0")->orderBy('id', 'DESC')->limit(3)->findAll();
    }
    public function obtenerporID($id)
    {
        return $this->where('institucion_id', $id)->orderBy('id', 'DESC')->findAll();
    }
}
