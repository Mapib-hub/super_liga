<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateGoles extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'             => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'jugador_id'     => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'institucion_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'partido_id'     => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'serie_id'       => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'cantidad'       => ['type' => 'INT', 'constraint' => 2, 'default' => 1],
            'created_at'     => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('goles');
    }

    public function down()
    {
        //
    }
}
