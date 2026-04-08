<?php

namespace App\Models;

use CodeIgniter\Model;

class SerieModel extends Model
{
    protected $table = 'series';
    protected $primaryKey = 'id';

    // ELIMINAMOS created_at, updated_at y deleted_at de aquí.
    // CI4 los maneja solo. Si los dejas aquí, los sobreescribe mal al insertar.
    protected $allowedFields = [
        'nombre',
        'descripcion',
        'slug',
        'alias' // Veo en tu captura que esta columna existe en la DB
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';
    protected $useSoftDeletes = true;

    protected $beforeInsert = ['generarSlug'];
    protected $beforeUpdate = ['generarSlug'];

    protected function generarSlug(array $data)
    {
        if (isset($data['data']['nombre'])) {
            // Limpieza básica para el slug
            $baseSlug = url_title(iconv('UTF-8', 'ASCII//TRANSLIT', $data['data']['nombre']), '-', true);
            $slug = $baseSlug;
            $contador = 1;

            $idActual = $data['id'] ?? null;

            // Usamos asArray() para evitar conflictos de objeto si fuera el caso
            while ($registro = $this->asArray()->where('slug', $slug)->first()) {
                if ($idActual && $registro['id'] == $idActual) {
                    break;
                }
                $slug = $baseSlug . '-' . $contador;
                $contador++;
            }

            $data['data']['slug'] = $slug;
        }

        return $data;
    }

    public function obtenerPorSlug($slug)
    {
        return $this->where('slug', esc($slug))->first();
    }
}