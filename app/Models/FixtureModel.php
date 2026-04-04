<?php
namespace App\Models;

use CodeIgniter\Model;

class FixtureModel extends Model
{
    protected $table      = 'fixture_principal';
    protected $primaryKey = 'id';

    protected $allowedFields = ['fecha_id', 'institucion_local_id', 'institucion_visitante_id', 'estadio', 'created_at', 'updated_at'];
    
    protected $useTimestamps = true;
}