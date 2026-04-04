<?php

namespace App\Models;

use CodeIgniter\Model;

class JugadorModel extends Model
{
    protected $table = 'jugadores';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'institucion_id',
        'nombres',
        'apellidos',
        'slug',
        'fecha_nacimiento',
        'rut_dni',
        'posicion',
        'tarjetas_amarillas',
        'tarjetas_rojas',
        'fecha_suspension_hasta',
        'numero_camiseta',
        'foto_path',
        'activo',
        'created_at',
        'updated_at',
        'deleted_at'
    ];
    protected $beforeInsert = ['generarSlug'];
    protected $beforeUpdate = ['generarSlug'];

    protected function generarSlug(array $data)
    {
        if (isset($data['data']['nombres']) && isset($data['data']['apellidos'])) {
            $nombreCompleto = $data['data']['nombres'] . ' ' . $data['data']['apellidos'];
            $slug = url_title(iconv('UTF-8', 'ASCII//TRANSLIT', $nombreCompleto), '-', true);
            $data['data']['slug'] = $slug;
        }

        return $data;
    }
    public function obtenerPorSlug($slug)
    {
        return $this->where('slug', esc($slug))->first();
    }

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';

    protected $useSoftDeletes = true;
}
