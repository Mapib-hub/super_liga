<?php

namespace App\Models;

use CodeIgniter\Model;

class TemporadaModel extends Model
{
    protected $table            = 'temporadas';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = ['nombre_temporada', 'actual'];

    // Fechas automáticas
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = ''; // No tienes updated_at en tu esquema

    /**
     * Método para establecer la temporada actual y desactivar el resto
     */
    public function setActual($id)
    {
        // Ponemos todas las temporadas en actual = 0
        $this->builder()->update(['actual' => 0]);

        // Ponemos la seleccionada en actual = 1
        return $this->update($id, ['actual' => 1]);
    }

    /**
     * Obtener la temporada activa
     */
    public function getTemporadaActual()
    {
        return $this->where('actual', 1)->first();
    }
}