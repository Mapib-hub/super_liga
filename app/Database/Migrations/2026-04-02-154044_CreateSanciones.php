<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSanciones extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'                => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'partido_id'        => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'institucion_id'    => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'jugador_id'        => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'tipo_tarjeta'      => ['type' => 'ENUM', 'constraint' => ['amarilla', 'roja', 'azul'], 'default' => 'amarilla'],
            'fechas_suspension' => ['type' => 'INT', 'constraint' => 3, 'default' => 0],
            'observacion'       => ['type' => 'TEXT', 'null' => true],
            'estado'            => ['type' => 'VARCHAR', 'constraint' => 50, 'default' => 'pendiente'],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('sanciones');
    }

    public function down()
    {
        //
    }
}
