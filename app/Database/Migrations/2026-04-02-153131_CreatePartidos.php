<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePartidos extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'           => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'principal_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true], // De tu modelo
            'temporada_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'serie_id'     => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true],
            'fecha_id'     => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'institucion_local_id'     => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'institucion_visitante_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'nombre_local_libre' => ['type' => 'VARCHAR', 'constraint' => 150, 'null' => true], // Para amistosos
            'nombre_visita_libre' => ['type' => 'VARCHAR', 'constraint' => 150, 'null' => true],
            'goles_local'   => ['type' => 'INT', 'constraint' => 3, 'default' => 0],
            'goles_visita'  => ['type' => 'INT', 'constraint' => 3, 'default' => 0],
            'penales_local'  => ['type' => 'INT', 'constraint' => 3, 'null' => true],
            'penales_visita' => ['type' => 'INT', 'constraint' => 3, 'null' => true],
            'estado'        => ['type' => 'ENUM', 'constraint' => ['pendiente', 'jugado', 'suspendido'], 'default' => 'pendiente'],
            'fase'          => ['type' => 'VARCHAR', 'constraint' => 50, 'default' => 'Regular'],
            'hora'          => ['type' => 'TIME', 'null' => true],
            'fecha_calendario' => ['type' => 'DATE', 'null' => true],
            'estadio'       => ['type' => 'VARCHAR', 'constraint' => 150, 'null' => true],
            'observaciones' => ['type' => 'TEXT', 'null' => true],
            'created_at'    => ['type' => 'DATETIME', 'null' => true],
            'updated_at'    => ['type' => 'DATETIME', 'null' => true],
        ]);

        $this->forge->addKey('id', true); // Define la Primary Key

        // Creamos la tabla
        $this->forge->createTable('partidos');
    }

    public function down()
    {
        $this->forge->dropTable('partidos');
    }
}
