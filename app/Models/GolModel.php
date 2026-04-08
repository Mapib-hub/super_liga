<?php

namespace App\Models;

use CodeIgniter\Model;

class GolModel extends Model
{
    protected $table      = 'goles';
    protected $primaryKey = 'id';
    protected $allowedFields = ['jugador_id', 'institucion_id', 'partido_id', 'serie_id', 'cantidad'];
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = ''; // No necesitamos updated_at para un gol

    public function getListadoGoles()
    {
        // Usamos el builder directamente desde el modelo para asegurar que no haya filtros previos
        $builder = $this->builder();

        $builder->select('goles.id, goles.jugador_id, j.nombres, j.apellidos, i.nombre as club, i.logo_path as club_logo, goles.cantidad, s.nombre as serie, p.fecha_calendario');
        $builder->join('jugadores j', 'goles.jugador_id = j.id');
        $builder->join('instituciones i', 'goles.institucion_id = i.id');
        $builder->join('series s', 'goles.serie_id = s.id');
        $builder->join('partidos p', 'goles.partido_id = p.id', 'left');
        $builder->orderBy('goles.id', 'DESC');

        // Cambiamos findAll() por get()->getResultArray() para saltarnos cualquier restricción del modelo base
        return $builder->get()->getResultArray();
    }
}
