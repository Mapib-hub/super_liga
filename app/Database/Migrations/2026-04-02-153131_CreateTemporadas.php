<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTemporadas extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'               => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'nombre_temporada' => ['type' => 'VARCHAR', 'constraint' => 100], // Ej: "Temporada 2024"
            'actual'           => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0], // 1 para la vigente
            'configuracion'    => ['type' => 'JSON', 'null' => true], // Por si quieres guardar ajustes extra
            'created_at'       => ['type' => 'DATETIME', 'null' => true],
            'updated_at'       => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('temporadas');
    }

    public function down()
    {
        $this->forge->dropTable('temporadas');
    }
}
