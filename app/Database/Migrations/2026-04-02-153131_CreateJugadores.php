<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateJugadores extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'                     => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'institucion_id'         => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'nombres'                => ['type' => 'VARCHAR', 'constraint' => 100],
            'apellidos'              => ['type' => 'VARCHAR', 'constraint' => 100],
            'slug'                   => ['type' => 'VARCHAR', 'constraint' => 255],
            'fecha_nacimiento'       => ['type' => 'DATE', 'null' => true],
            'rut_dni'                => ['type' => 'VARCHAR', 'constraint' => 20, 'null' => true],
            'posicion'               => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            'tarjetas_amarillas'     => ['type' => 'INT', 'constraint' => 11, 'default' => 0],
            'tarjetas_rojas'         => ['type' => 'INT', 'constraint' => 11, 'default' => 0],
            'fecha_suspension_hasta' => ['type' => 'DATE', 'null' => true],
            'numero_camiseta'        => ['type' => 'INT', 'constraint' => 3, 'null' => true],
            'foto_path'              => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'activo'                 => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1],
            'created_at'             => ['type' => 'DATETIME', 'null' => true],
            'updated_at'             => ['type' => 'DATETIME', 'null' => true],
            'deleted_at'             => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('jugadores');
    }

    public function down()
    {
        //
    }
}
