<?php

namespace App\Models;

use CodeIgniter\Model;

class InstitucionModel extends Model
{
    protected $table      = 'instituciones';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'nombre',
        'direccion',
        'telefono',
        'estadio',
        'direccion_estadio',
        'email_contacto',
        'nombre_contacto',
        'logo_path',
        'descripcion',
        'slug',
        'fundacion',
        'maps',
        'razon_social'
    ];

    protected $useTimestamps   = true;
    protected $createdField    = 'created_at';
    protected $updatedField    = 'updated_at';
    protected $deletedField    = 'deleted_at';
    protected $useSoftDeletes  = true;

    // Eventos de modelo
    protected $beforeInsert = ['generarSlug'];
    protected $beforeUpdate = ['generarSlug'];

    public function obtenerTodas()
    {
        return $this->findAll();
    }

    public function obtenerPorSlug($slug)
    {
        return $this->where('slug', esc($slug))->first();
    }

    /**
     * Lógica para generar Slugs automáticos y únicos
     */
    protected function generarSlug(array $data)
    {
        // Solo actuamos si el nombre viene en el set de datos
        if (isset($data['data']['nombre'])) {
            $nombreNuevo = $data['data']['nombre'];

            // CI4 puede pasar el ID como un valor simple o dentro de un array
            $idActual = null;
            if (isset($data['id'])) {
                $idActual = is_array($data['id']) ? ($data['id'][0] ?? null) : $data['id'];
            }

            // 1. Si es un UPDATE, verificamos si el nombre cambió realmente
            if ($idActual) {
                $registroActual = $this->asArray()->find($idActual);

                // Si el nombre es el mismo, no tocamos el slug y retornamos
                if ($registroActual && $registroActual['nombre'] === $nombreNuevo) {
                    return $data;
                }
            }

            // 2. Preparar el Slug base (limpiando tildes y caracteres raros)
            $slugBase = url_title(iconv('UTF-8', 'ASCII//TRANSLIT', $nombreNuevo), '-', true);
            $slugFinal = $slugBase;
            $contador  = 1;

            // 3. Bucle para asegurar que el slug sea único en la tabla
            // Usamos asArray() para evitar líos con objetos/entidades
            while (true) {
                $builder = $this->asArray()->where('slug', $slugFinal);

                // Si estamos editando, ignoramos el registro actual en la búsqueda de duplicados
                if ($idActual) {
                    $builder->where('id !=', $idActual);
                }

                $existe = $builder->first();

                if (!$existe) {
                    break; // El slug está disponible
                }

                // Si existe, le agregamos el contador
                $slugFinal = $slugBase . '-' . $contador;
                $contador++;
            }

            // 4. Asignamos el slug final a los datos que se van a guardar
            $data['data']['slug'] = $slugFinal;
        }

        return $data;
    }
    public function getEstadisticasCompletas(int $idInstitucion, int $idTemporada)
    {
        $db = \Config\Database::connect();

        $partidos = $db->table('partidos')
            ->select('partidos.*, series.nombre as nombre_serie')
            ->join('series', 'series.id = partidos.serie_id')
            ->where('temporada_id', $idTemporada)
            ->groupStart()
            ->where('institucion_local_id', $idInstitucion)
            ->orWhere('institucion_visitante_id', $idInstitucion)
            ->groupEnd()
            ->get()->getResultArray();

        $statsSeries = [];
        $totales = ['pj' => 0, 'pg' => 0, 'pe' => 0, 'pp' => 0, 'gf' => 0, 'gc' => 0, 'pts' => 0];

        foreach ($partidos as $p) {
            $serieNom = $p['nombre_serie'];

            if (!isset($statsSeries[$serieNom])) {
                $statsSeries[$serieNom] = ['pj' => 0, 'pg' => 0, 'pe' => 0, 'pp' => 0, 'gf' => 0, 'gc' => 0, 'pts' => 0];
            }

            $esLocal = ($p['institucion_local_id'] == $idInstitucion);
            $gP = $esLocal ? $p['goles_local'] : $p['goles_visita'];
            $gR = $esLocal ? $p['goles_visita'] : $p['goles_local'];

            $puntos = ($gP > $gR) ? 3 : (($gP == $gR) ? 1 : 0);
            $resultado = ($gP > $gR) ? 'pg' : (($gP < $gR) ? 'pp' : 'pe');

            // --- CÁLCULO SEGURO SIN EL ERROR DEL "&" ---

            // Sumar a la Serie específica
            $statsSeries[$serieNom]['pj']++;
            $statsSeries[$serieNom]['gf']  += $gP;
            $statsSeries[$serieNom]['gc']  += $gR;
            $statsSeries[$serieNom][$resultado]++;
            $statsSeries[$serieNom]['pts'] += $puntos;

            // Sumar al Total General
            $totales['pj']++;
            $totales['gf']  += $gP;
            $totales['gc']  += $gR;
            $totales[$resultado]++;
            $totales['pts'] += $puntos;
        }

        return [
            'resumen'     => $totales,
            'statsSeries' => $statsSeries
        ];
    }
}
