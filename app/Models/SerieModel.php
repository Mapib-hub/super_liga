<?php
namespace App\Models;

use CodeIgniter\Model;

class SerieModel extends Model
{
    protected $table = 'series';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'nombre',
        'descripcion',
        'slug',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';
    protected $useSoftDeletes = true;

    protected $beforeInsert = ['generarSlug'];
    protected $beforeUpdate = ['generarSlug'];

    protected function generarSlug(array $data)
    {
        if (isset($data['data']['nombre'])) {
            $baseSlug = url_title(iconv('UTF-8', 'ASCII//TRANSLIT', $data['data']['nombre']), '-', true);
            $slug = $baseSlug;
            $contador = 1;

            // Detectar si estamos editando
            $idActual = $data['id'] ?? null;

            while ($registro = $this->where('slug', $slug)->first()) {
                // Si el slug ya existe pero pertenece al mismo registro, lo dejamos
                if ($idActual && $registro['id'] == $idActual) {
                    break;
                }

                // Si pertenece a otro registro, generamos uno nuevo
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