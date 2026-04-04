<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSeries extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'          => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'nombre'      => ['type' => 'VARCHAR', 'constraint' => 100], // Ej: "Senior 35"
            'slug'        => ['type' => 'VARCHAR', 'constraint' => 100, 'unique' => true],
            'orden'       => ['type' => 'INT', 'constraint' => 2, 'default' => 0], // Para que salgan en orden en el menú
            'puntos_ganado' => ['type' => 'INT', 'constraint' => 2, 'default' => 3],
            'activo'      => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1],
            'created_at'  => ['type' => 'DATETIME', 'null' => true],
            'updated_at'  => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('series');
    }

    public function down()
    {
        $this->forge->dropTable('series');
    }
}
