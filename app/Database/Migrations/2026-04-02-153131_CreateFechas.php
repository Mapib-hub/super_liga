<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateFechas extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'                => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'temporada_id'      => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'nombre_fecha'      => ['type' => 'VARCHAR', 'constraint' => 100],
            'fecha_calendario'  => ['type' => 'DATE', 'null' => true],
            'estado'            => ['type' => 'ENUM', 'constraint' => ['pendiente', 'jugada', 'suspendida'], 'default' => 'pendiente'],
            'estado_infantiles' => ['type' => 'ENUM', 'constraint' => ['pendiente', 'jugada', 'suspendida'], 'default' => 'pendiente'],
            'created_at'        => ['type' => 'DATETIME', 'null' => true],
            'updated_at'        => ['type' => 'DATETIME', 'null' => true],
            'deleted_at'        => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('fechas');
    }

    public function down()
    {
        //
    }
}
