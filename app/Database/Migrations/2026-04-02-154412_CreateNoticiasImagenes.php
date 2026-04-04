<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateNoticiasImagenes extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'          => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'noticia_id'  => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'imagen_path' => ['type' => 'VARCHAR', 'constraint' => 255], // Aquí guardarás la URL de Cloudinary
            'public_id'   => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true], // Tip Senior: Guarda el ID de Cloudinary por si necesitas borrarla después
            'created_at'  => ['type' => 'DATETIME', 'null' => true],
            'updated_at'  => ['type' => 'DATETIME', 'null' => true],
        ]);

        $this->forge->addKey('id', true);

        // Creamos la tabla
        $this->forge->createTable('noticias_imagenes');
    }

    public function down()
    {
        $this->forge->dropTable('noticias_imagenes');
    }
}
