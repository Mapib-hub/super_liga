<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateNoticias extends Migration
{
    public function up()
    {
        // Tabla Noticias
        $this->forge->addField([
            'id'             => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'titulo'         => ['type' => 'VARCHAR', 'constraint' => 255],
            'descripcion'    => ['type' => 'TEXT'],
            'imagen'         => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'fecha_creacion' => ['type' => 'DATETIME', 'null' => true],
            'institucion_id' => ['type' => 'INT', 'constraint' => 11, 'default' => 0],
            'slug'           => ['type' => 'VARCHAR', 'constraint' => 255, 'unique' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('noticias');

        // Tabla Noticias Imágenes (Relación 1 a muchos para galerías)
        $this->forge->addField([
            'id'          => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'noticia_id'  => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'imagen_path' => ['type' => 'VARCHAR', 'constraint' => 255],
            'created_at'  => ['type' => 'DATETIME', 'null' => true],
            'updated_at'  => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('noticias_imagenes');
    }

    public function down()
    {
        //
    }
}
