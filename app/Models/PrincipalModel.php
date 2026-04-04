<?php

namespace App\Models;

use CodeIgniter\Model;

class PrincipalModel extends Model
{
    protected $table      = 'principal'; // Tu tabla maestra
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'fecha_id',
        'temporada_id',
        'institucion_local_id',
        'institucion_visitante_id',
        'estadio',
        'fecha_cal'
    ];


    public function getFixtureGlobal($fecha_id, $temporada_id)
    {
        return $this->select('principal.*, f.nombre_fecha, 
                              i_loc.nombre as local, i_loc.logo_path as logo_local, 
                              i_vis.nombre as visitante, i_vis.logo_path as logo_visitante')
            ->join('fechas f', 'f.id = principal.fecha_id')
            ->join('instituciones i_loc', 'i_loc.id = principal.institucion_local_id')
            ->join('instituciones i_vis', 'i_vis.id = principal.institucion_visitante_id')
            ->where('principal.temporada_id', $temporada_id)
            ->where('principal.fecha_id', $fecha_id)
            // Priorizamos que aparezca el club ID 11 si existe en el cruce (opcional según tu lógica previa)
            ->orderBy('CASE 
                        WHEN principal.institucion_local_id = 11 THEN 1 
                        WHEN principal.institucion_visitante_id = 11 THEN 1 
                        ELSE 0 
                      END', 'DESC', false)
            ->findAll();
    }
    public function getDueloConNombres($id)
    {
        // dd($temporada_id);
        return $this->select('principal.*, l.nombre as local_nombre, v.nombre as visitante_nombre')
            ->join('instituciones l', 'l.id = principal.institucion_local_id')
            ->join('instituciones v', 'v.id = principal.institucion_visitante_id')
            ->where('principal.id', $id)
            ->first(); // Retorna un array de una sola fila
    }
    public function getFixtureAgrupado($temporada_id)
    {
        // 1. Guardamos el resultado en una variable (SIN el return todavía)
        $partidos = $this->select('
            principal.id, 
            principal.fecha_id,
            f.nombre_fecha, 
            i_loc.nombre as local, 
            i_loc.logo_path as logo_local, 
            i_vis.nombre as visitante, 
            i_vis.logo_path as logo_visitante
        ')
            ->join('fechas f', 'f.id = principal.fecha_id')
            ->join('instituciones i_loc', 'i_loc.id = principal.institucion_local_id')
            ->join('instituciones i_vis', 'i_vis.id = principal.institucion_visitante_id')
            ->where('principal.temporada_id', $temporada_id)
            ->orderBy('principal.fecha_id', 'ASC')
            ->orderBy('principal.id', 'ASC')
            ->findAll();

        // 2. Aquí hacemos la magia del agrupamiento
        $fixtureAgrupado = [];
        foreach ($partidos as $partido) {
            // Usamos el nombre de la fecha como la "llave" del cajón
            $fixtureAgrupado[$partido['nombre_fecha']][] = $partido;
        }

        // 3. AHORA sí, retornamos el resultado final ya organizado
        return $fixtureAgrupado;
    }
    public function obtenerPartidosConDetalles($fecha_id, $temporada_id)
    {
        return $this->select('
            principal.*, 
            f.nombre_fecha, 
            i_loc.nombre as local, 
            i_loc.logo_path as logo_local, 
            i_vis.nombre as visitante, 
            i_vis.logo_path as logo_visitante
        ')
            ->join('fechas f', 'f.id = principal.fecha_id')
            ->join('instituciones i_loc', 'i_loc.id = principal.institucion_local_id')
            ->join('instituciones i_vis', 'i_vis.id = principal.institucion_visitante_id')
            ->where('principal.temporada_id', $temporada_id)
            ->where('principal.fecha_id', $fecha_id)

            // El 'false' al final evita que CodeIgniter ponga comillas donde no debe
            ->orderBy('CASE 
                    WHEN principal.institucion_local_id = 11 THEN 1 
                    WHEN principal.institucion_visitante_id = 11 THEN 1 
                    ELSE 0 
                  END', 'DESC', false)

            ->orderBy('principal.id', 'ASC')
            ->findAll();
    }
}