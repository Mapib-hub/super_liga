<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePrincipal extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'                       => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'fecha_id'                 => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'temporada_id'             => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'institucion_local_id'     => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'institucion_visitante_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'estadio'                  => ['type' => 'VARCHAR', 'constraint' => 150, 'null' => true],
            'fecha_cal'                => ['type' => 'DATE', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('principal');
    }

    public function down()
    {
        //
    }
}
