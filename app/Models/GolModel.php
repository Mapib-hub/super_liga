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
}
