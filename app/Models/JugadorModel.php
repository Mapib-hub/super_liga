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
    /**
     * Obtiene todos los jugadores con el nombre de su institución vinculada.
     */
    public function obtenerTodosConInstitucion()
    {
        return $this->select('jugadores.*, instituciones.nombre as institucion_nombre')
            ->join('instituciones', 'instituciones.id = jugadores.institucion_id')
            ->orderBy('jugadores.id', 'DESC')
            ->findAll();
    }

    /**
     * Obtiene los jugadores de una institución específica (útil para delegados).
     */
    public function obtenerPorInstitucion($institucionId)
    {
        return $this->select('jugadores.*, instituciones.nombre as institucion_nombre')
            ->join('instituciones', 'instituciones.id = jugadores.institucion_id')
            ->where('jugadores.institucion_id', $institucionId)
            ->orderBy('jugadores.apellidos', 'ASC')
            ->findAll();
    }

    /**
     * Busca un jugador por ID incluyendo los datos de su club.
     */
    public function obtenerDetalle($id)
    {
        return $this->select('jugadores.*, instituciones.nombre as institucion_nombre')
            ->join('instituciones', 'instituciones.id = jugadores.institucion_id')
            ->find($id);
    }
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';

    protected $useSoftDeletes = true;
}
