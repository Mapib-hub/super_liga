<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateInstituciones extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'                => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'nombre'            => ['type' => 'VARCHAR', 'constraint' => 150],
            'direccion'         => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'telefono'          => ['type' => 'VARCHAR', 'constraint' => 20, 'null' => true],
            'estadio'           => ['type' => 'VARCHAR', 'constraint' => 150, 'null' => true],
            'direccion_estadio' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'email_contacto'    => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'nombre_contacto'   => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'logo_path'         => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'descripcion'       => ['type' => 'TEXT', 'null' => true],
            'slug'              => ['type' => 'VARCHAR', 'constraint' => 150, 'unique' => true],
            'fundacion'         => ['type' => 'DATE', 'null' => true],
            'maps'              => ['type' => 'TEXT', 'null' => true],
            'razon_social'      => ['type' => 'VARCHAR', 'constraint' => 150, 'null' => true],
            'created_at'        => ['type' => 'DATETIME', 'null' => true],
            'updated_at'        => ['type' => 'DATETIME', 'null' => true],
            'deleted_at'        => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('instituciones');
    }

    public function down()
    {
        //
    }
}
