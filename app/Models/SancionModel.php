<?php

namespace App\Models;

use CodeIgniter\Model;

class SancionModel extends Model
{
    protected $table      = 'sanciones'; // Asegúrate que este sea el nombre real
    protected $primaryKey = 'id';

    // ESTO ES LO QUE ESTÁ DANDO ERROR:
    protected $useTimestamps = false;

    // También asegúrate de que estos campos coincidan con tu DB
    protected $allowedFields = [
        'partido_id',
        'institucion_id',
        'jugador_id',
        'tipo_tarjeta',
        'fechas_suspension',
        'observacion',
        'estado'
    ];
}
