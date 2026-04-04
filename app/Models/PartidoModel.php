<?php

namespace App\Models;

use CodeIgniter\Model;

class PartidoModel extends Model
{
    protected $table      = 'partidos';
    protected $primaryKey = 'id';

    // Agregamos los campos que venían en tu SQL de partidos_2 (ahora partidos)
    protected $allowedFields = [
        'principal_id',
        'temporada_id',
        'serie_id',
        'nombre_serie_libre',
        'fecha_id',
        'institucion_local_id',
        'institucion_visitante_id',
        'nombre_local_libre',
        'nombre_visita_libre',
        'goles_local',
        'goles_visita',
        'fase',
        'estado',
        'fecha_calendario',
        'hora',
        'penales_local',
        'penales_visita',
        'estadio',
        'observaciones',
        'created_at'
    ];

    /**
     * Obtiene el total de goles de la temporada
     * @param int $temporada_id ID de la temporada
     * @return int Total de goles
     */
    public function getTotalGolesTemporada($temporada_id)
    {
        return $this->select('SUM(goles_local + goles_visita) as total_goles')
            ->where('temporada_id', $temporada_id)
            ->where('estado', 'jugado') // Solo partidos jugados
            ->get()
            ->getRow()
            ->total_goles ?? 0;
    }
    /**
     * Obtiene estadísticas completas de goles
     * @param int $temporada_id ID de la temporada
     * @return array Estadísticas
     */
    public function getEstadisticasGoles($temporada_id)
    {
        $totalGoles = $this->getTotalGolesTemporada($temporada_id);
        $golesPorSerie = $this->getGolesPorSerie($temporada_id);

        // Goles por fecha (tendencia)
        $golesPorFecha = $this->select('
        fechas.nombre_fecha,
        SUM(goles_local + goles_visita) as total_goles
    ')
            ->join('fechas', 'fechas.id = partidos.fecha_id')
            ->where('partidos.temporada_id', $temporada_id)
            ->where('partidos.estado', 'jugado')
            ->where('partidos.fase', 'Regular')
            ->groupBy('partidos.fecha_id')
            ->orderBy('fechas.id', 'ASC')
            ->findAll();

        return [
            'total' => $totalGoles,
            'por_serie' => $golesPorSerie,
            'por_fecha' => $golesPorFecha,
            'promedio' => $this->getPromedioGoles($temporada_id)
        ];
    }

    /**
     * Obtiene promedio de goles por partido
     * @param int $temporada_id ID de la temporada
     * @return float Promedio de goles
     */
    public function getPromedioGoles($temporada_id)
    {
        $totalPartidos = $this->where('temporada_id', $temporada_id)
            ->where('estado', 'jugado')
            ->countAllResults();

        $totalGoles = $this->getTotalGolesTemporada($temporada_id);

        return $totalPartidos > 0 ? round($totalGoles / $totalPartidos, 2) : 0;
    }

    public function getPartidosFull($temporada_id, $id)
    {

        $builder = $this->select('
            partidos.*, 
            series.nombre as serie_nombre, 
            series.slug as serie_slug,
            fechas.nombre_fecha, 
            fechas.fecha_calendario as dia_fecha,
            locales.nombre as local_nombre, 
            visitantes.nombre as visitante_nombre,
            locales.logo_path as local_logo,
            visitantes.logo_path as visitante_logo
        ');

        $builder->join('series', 'series.id = partidos.serie_id');
        $builder->join('fechas', 'fechas.id = partidos.fecha_id');
        $builder->join('instituciones as locales', 'locales.id = partidos.institucion_local_id');
        $builder->join('instituciones as visitantes', 'visitantes.id = partidos.institucion_visitante_id');

        // Filtro de temporada obligatorio
        $builder->where('partidos.temporada_id', $temporada_id);

        if ($id) {
            $builder->where('series.id', $id);
        }

        return $builder->orderBy('fechas.id', 'ASC')->findAll();
    }
    public function getPartidoById($id)
    {
        return $this->select('
            partidos.*, 
            series.nombre as serie_nombre, 
            series.slug as serie_slug,
            fechas.nombre_fecha, 
            fechas.fecha_calendario as dia_fecha,
            locales.nombre as local_nombre, 
            visitantes.nombre as visitante_nombre,
            locales.logo_path as local_logo,
            visitantes.logo_path as visitante_logo,
            temporadas.nombre_temporada as temporada_nombre
        ')
            ->join('series', 'series.id = partidos.serie_id', 'left')
            ->join('fechas', 'fechas.id = partidos.fecha_id', 'left')
            ->join('temporadas', 'temporadas.id = partidos.temporada_id', 'left')
            ->join('instituciones as locales', 'locales.id = partidos.institucion_local_id', 'left')
            ->join('instituciones as visitantes', 'visitantes.id = partidos.institucion_visitante_id', 'left')
            ->where('partidos.id', $id)
            ->first(); // first() para un solo registro
    }
    public function getPartidosEspeciales($temporada_id = null)
    { // Si no se proporciona temporada, podrías obtener la activa
        if ($temporada_id === null) {
            $temporadaModel = new \App\Models\TemporadaModel();
            $temporadaActiva = $temporadaModel->where('actual', 1)->first();
            $temporada_id = $temporadaActiva ? $temporadaActiva['id'] : 0;
        }

        return $this->select("
            partidos.*, 
            temporadas.nombre_temporada as temporada_nombre,
            IFNULL(partidos.nombre_serie_libre, series.nombre) as nombre_serie,
            IFNULL(partidos.nombre_local_libre, il.nombre) as nombre_local,
            IFNULL(partidos.nombre_visita_libre, iv.nombre) as nombre_visitante,
            il.logo_path as escudo_local,
            iv.logo_path as escudo_visitante
        ")
            ->join('temporadas', 'temporadas.id = partidos.temporada_id') // 👈 CORREGIDO
            ->join('series', 'series.id = partidos.serie_id', 'left')
            ->join('instituciones il', 'il.id = partidos.institucion_local_id', 'left')
            ->join('instituciones iv', 'iv.id = partidos.institucion_visitante_id', 'left')
            ->where('partidos.temporada_id', $temporada_id) // 👈 AGREGADO FILTRO POR TEMPORADA
            ->whereNotIn('partidos.fase', ['Regular'])
            ->orderBy('partidos.fecha_calendario', 'DESC')
            ->findAll();
    }
    public function getPartidosEspeciales_id($fecha_id = null)
    {
        $builder = $this->select("
            partidos.*, 
            IFNULL(partidos.nombre_serie_libre, series.nombre) as nombre_serie,
            IFNULL(partidos.nombre_local_libre, il.nombre) as nombre_local,
            IFNULL(partidos.nombre_visita_libre, iv.nombre) as nombre_visitante,
            il.logo_path as escudo_local,
            iv.logo_path as escudo_visitante
        ")
            ->join('series', 'partidos.serie_id = series.id', 'left')
            ->join('instituciones il', 'partidos.institucion_local_id = il.id', 'left')
            ->join('instituciones iv', 'partidos.institucion_visitante_id = iv.id', 'left')
            ->whereIn('partidos.estado', ['pendiente'])
            ->whereIn('partidos.fase', ['Amistoso', 'Liguilla', 'Definición']);

        if ($fecha_id) {
            $builder->where('partidos.fecha_id', $fecha_id);
        }

        return $builder->orderBy('partidos.hora', 'ASC')->findAll();
    }

    public function getPartidosAnfa()
    {
        // Calculamos la fecha de hace 3 semanas
        $fechaLimite = date('Y-m-d', strtotime('-3 weeks'));

        $builder = $this->select("
            partidos.*, 
            IFNULL(partidos.nombre_serie_libre, series.nombre) as nombre_serie,
            IFNULL(partidos.nombre_local_libre, il.nombre) as nombre_local,
            IFNULL(partidos.nombre_visita_libre, iv.nombre) as nombre_visitante,
            il.logo_path as escudo_local,
            iv.logo_path as escudo_visitante
        ")
            ->join('series', 'partidos.serie_id = series.id', 'left')
            ->join('instituciones il', 'partidos.institucion_local_id = il.id', 'left')
            ->join('instituciones iv', 'partidos.institucion_visitante_id = iv.id', 'left')
            ->whereIn('partidos.fase', ['Regional', 'Nacional'])
            // Agregamos el alias de la tabla 'partidos.' antes de la columna
            ->where('partidos.created_at >=', $fechaLimite);

        return $builder->orderBy('partidos.fecha_calendario', 'DESC')->findAll();
    }

    public function getTablaPosiciones($serie_id, $temporada_id)
    {
        //dd($temporada_id);
        $sql = "SELECT 
                res.id, res.nombre, res.slug, res.escudo,
                SUM(res.jugados) as PJ, 
                SUM(res.ganados) as PG, 
                SUM(res.empatados) as PE, 
                SUM(res.perdidos) as PP,
                SUM(res.goles_f) as GF, 
                SUM(res.goles_c) as GC, 
                (SUM(res.goles_f) - SUM(res.goles_c)) as DG,
                (SUM(res.ganados) * 3 + SUM(res.empatados)) as PTS
            FROM (
                -- BLOQUE LOCAL
                SELECT 
                    i.id, i.nombre, i.slug, i.logo_path as escudo,
                    COUNT(p.id) as jugados,
                    SUM(CASE WHEN p.goles_local > p.goles_visita THEN 1 ELSE 0 END) as ganados,
                    SUM(CASE WHEN p.goles_local = p.goles_visita THEN 1 ELSE 0 END) as empatados,
                    SUM(CASE WHEN p.goles_local < p.goles_visita THEN 1 ELSE 0 END) as perdidos,
                    SUM(IFNULL(p.goles_local, 0)) as goles_f,
                    SUM(IFNULL(p.goles_visita, 0)) as goles_c
                FROM instituciones i
                INNER JOIN partidos p ON i.id = p.institucion_local_id
                WHERE p.serie_id = ? AND p.temporada_id = ? AND p.fase = 'Regular' AND p.estado = 'jugado'
                GROUP BY i.id

                UNION ALL
                -- BLOQUE VISITA
                SELECT 
                    i.id, i.nombre, i.slug, i.logo_path as escudo,
                    COUNT(p.id) as jugados,
                    SUM(CASE WHEN p.goles_visita > p.goles_local THEN 1 ELSE 0 END) as ganados,
                    SUM(CASE WHEN p.goles_visita = p.goles_local THEN 1 ELSE 0 END) as empatados,
                    SUM(CASE WHEN p.goles_visita < p.goles_local THEN 1 ELSE 0 END) as perdidos,
                    SUM(IFNULL(p.goles_visita, 0)) as goles_f,
                    SUM(IFNULL(p.goles_local, 0)) as goles_c
                FROM instituciones i
                INNER JOIN partidos p ON i.id = p.institucion_visitante_id
                WHERE p.serie_id = ? AND p.temporada_id = ? AND p.fase = 'Regular' AND p.estado = 'jugado'
                GROUP BY i.id
            ) as res
            GROUP BY res.id, res.nombre, res.slug, res.escudo
            ORDER BY PTS DESC, DG DESC, GF DESC";

        return $this->db->query($sql, [$serie_id, $temporada_id, $serie_id, $temporada_id])->getResultArray();
    }

    public function getTablaGeneralInstituciones($temporada_id)
    {
        $sql = "SELECT 
            res.id, res.nombre, res.escudo,
            SUM(res.jugados) as PJ, 
            SUM(res.ganados) as PG, 
            SUM(res.empatados) as PE, 
            SUM(res.perdidos) as PP,
            SUM(res.goles_f) as GF, 
            SUM(res.goles_c) as GC, 
            (SUM(res.goles_f) - SUM(res.goles_c)) as DG,
            (SUM(res.ganados) * 3 + SUM(res.empatados)) as PTS
        FROM (
            -- BLOQUE LOCAL: Filtramos por temporada y estado jugado
            SELECT 
                i.id, i.nombre, i.logo_path as escudo,
                COUNT(p.id) as jugados,
                SUM(CASE WHEN p.goles_local > p.goles_visita THEN 1 ELSE 0 END) as ganados,
                SUM(CASE WHEN p.goles_local = p.goles_visita THEN 1 ELSE 0 END) as empatados,
                SUM(CASE WHEN p.goles_local < p.goles_visita THEN 1 ELSE 0 END) as perdidos,
                SUM(IFNULL(p.goles_local, 0)) as goles_f,
                SUM(IFNULL(p.goles_visita, 0)) as goles_c
            FROM instituciones i
            INNER JOIN partidos p ON i.id = p.institucion_local_id
            WHERE p.temporada_id = ? AND p.fase = 'Regular' AND p.estado = 'jugado'
            GROUP BY i.id

            UNION ALL

            -- BLOQUE VISITA: Filtramos por temporada y estado jugado
            SELECT 
                i.id, i.nombre, i.logo_path as escudo,
                COUNT(p.id) as jugados,
                SUM(CASE WHEN p.goles_visita > p.goles_local THEN 1 ELSE 0 END) as ganados,
                SUM(CASE WHEN p.goles_visita = p.goles_local THEN 1 ELSE 0 END) as empatados,
                SUM(CASE WHEN p.goles_visita < p.goles_local THEN 1 ELSE 0 END) as perdidos,
                SUM(IFNULL(p.goles_visita, 0)) as goles_f,
                SUM(IFNULL(p.goles_local, 0)) as goles_c
            FROM instituciones i
            INNER JOIN partidos p ON i.id = p.institucion_visitante_id
            WHERE p.temporada_id = ? AND p.fase = 'Regular' AND p.estado = 'jugado'
            GROUP BY i.id
        ) as res
        GROUP BY res.id, res.nombre, res.escudo
        ORDER BY PTS DESC, DG DESC, GF DESC";

        // Usamos $this->db->query para seguir las buenas prácticas del modelo
        return $this->db->query($sql, [$temporada_id, $temporada_id])->getResultArray();
    }
    // --- NUEVAS FUNCIONES PARA EL DASHBOARD ---

    public function getConteosGenerales($temporada_id)
    {
        return [
            'total'      => $this->where('temporada_id', $temporada_id)->countAllResults(),
            'jugados'    => $this->where(['temporada_id' => $temporada_id, 'estado' => 'jugado'])->countAllResults(),
            'pendientes' => $this->where(['temporada_id' => $temporada_id, 'estado' => 'pendiente'])->countAllResults(),
        ];
    }

    public function getGolesPorSerie($temporada_id)
    {
        return $this->select('series.nombre, SUM(goles_local + goles_visita) as goles')
            ->join('series', 'series.id = partidos.serie_id')
            ->where(['partidos.temporada_id' => $temporada_id, 'partidos.estado' => 'jugado', 'partidos.fase' => 'Regular'])
            ->groupBy('partidos.serie_id')
            ->findAll();
    }

    public function getPartidosPorSerie($temporada_id)
    {
        return $this->select('series.nombre, COUNT(partidos.id) as total')
            ->join('series', 'series.id = partidos.serie_id')
            ->where('partidos.temporada_id', $temporada_id)
            ->groupBy('partidos.serie_id')
            ->findAll();
    }

    public function getGolesPorFecha($temporada_id)
    {
        return $this->select('fechas.nombre_fecha as jornada, SUM(goles_local + goles_visita) as total_goles')
            ->join('fechas', 'fechas.id = partidos.fecha_id')
            ->where(['partidos.temporada_id' => $temporada_id, 'partidos.estado' => 'jugado'])
            ->groupBy('partidos.fecha_id')
            ->orderBy('fechas.id', 'ASC')
            ->findAll();
    }
    public function getFixturePorCategoria($fecha_id, $esInfantil = false)
    {
        // 1. Traemos todos los especiales de esa fecha
        $todos = $this->getPartidosEspeciales_id($fecha_id);

        $ids_infantiles = [1, 2, 3]; // IDs de series infantiles

        // 2. Filtramos
        $filtrados = array_filter($todos, function ($p) use ($ids_infantiles, $esInfantil) {
            $esDeInfantil = in_array($p['serie_id'], $ids_infantiles);
            return $esInfantil ? $esDeInfantil : !$esDeInfantil;
        });

        // 3. ¡IMPORTANTE! Usamos array_values para que los índices sean 0, 1, 2... 
        // Sin esto, la vista a veces no los muestra.
        return array_values($filtrados);
    }


    public function getPartidosfechaSerie($serie, $fecha)
    {

        $builder = $this->select('
            partidos.*, 
            series.nombre as serie_nombre, 
            series.slug as serie_slug,
            fechas.nombre_fecha, 
            fechas.fecha_calendario as dia_fecha,
            locales.nombre as local_nombre, 
            visitantes.nombre as visitante_nombre,
            locales.logo_path as local_logo,
            visitantes.logo_path as visitante_logo
        ');

        $builder->join('series', 'series.id = partidos.serie_id');
        $builder->join('fechas', 'fechas.id = partidos.fecha_id');
        $builder->join('instituciones as locales', 'locales.id = partidos.institucion_local_id');
        $builder->join('instituciones as visitantes', 'visitantes.id = partidos.institucion_visitante_id');

        // Filtro de temporada obligatorio
        $builder->where('partidos.fecha_id', $fecha);

        if ($serie) {
            $builder->where('series.id', $serie);
        }

        return $builder->orderBy('id', 'ASC')->findAll();
    }
    public function getGoleadoresPorSerie($temporada_id, $serie_id = null)
    {
        $builder = $this->db->table('goles g');
        $builder->select('
        g.jugador_id,
        j.nombres,
        j.apellidos,
        i.nombre as club,
        i.logo_path as escudo,
        s.nombre as serie,
        s.id as serie_id,
        SUM(g.cantidad) as total_goles
    ');
        $builder->join('jugadores j', 'g.jugador_id = j.id');
        $builder->join('instituciones i', 'g.institucion_id = i.id');
        $builder->join('series s', 'g.serie_id = s.id');
        $builder->join('partidos p', 'g.partido_id = p.id');
        $builder->where('p.temporada_id', $temporada_id);

        if ($serie_id) {
            $builder->where('g.serie_id', $serie_id);
        }

        $builder->groupBy('g.jugador_id');
        $builder->orderBy('total_goles', 'DESC');
        $builder->limit(10);

        return $builder->get()->getResultArray();
    }

    /**
     * Obtiene el total de partidos de la temporada
     * @param int $temporada_id ID de la temporada
     * @return int Total de partidos
     */
    public function getTotalPartidosTemporada($temporada_id)
    {
        return $this->where('temporada_id', $temporada_id)
            ->countAllResults();
    }
    /**
     * Obtiene conteo de partidos por estado
     * @param int $temporada_id ID de la temporada
     * @return array Conteo de partidos (total, jugados, pendientes, suspendidos)
     */
    public function getConteoPartidosPorEstado($temporada_id)
    {
        $total = $this->where('temporada_id', $temporada_id)->countAllResults();
        $jugados = $this->where('temporada_id', $temporada_id)
            ->where('estado', 'jugado')
            ->countAllResults();
        $pendientes = $this->where('temporada_id', $temporada_id)
            ->where('estado', 'pendiente')
            ->countAllResults();
        $suspendidos = $this->where('temporada_id', $temporada_id)
            ->where('estado', 'suspendido')
            ->countAllResults();

        return [
            'total' => $total,
            'jugados' => $jugados,
            'pendientes' => $pendientes,
            'suspendidos' => $suspendidos
        ];
    }

    /**
     * Obtiene partidos por serie (solo conteo)
     * @param int $temporada_id ID de la temporada
     * @return array Partidos por serie
     */
    public function getPartidosPorSerieStats($temporada_id)
    {
        return $this->select('
        series.nombre as serie,
        series.id as serie_id,
        COUNT(partidos.id) as total_partidos,
        SUM(CASE WHEN partidos.estado = "jugado" THEN 1 ELSE 0 END) as jugados,
        SUM(CASE WHEN partidos.estado = "pendiente" THEN 1 ELSE 0 END) as pendientes
    ')
            ->join('series', 'series.id = partidos.serie_id')
            ->where('partidos.temporada_id', $temporada_id)
            ->groupBy('partidos.serie_id')
            ->orderBy('total_partidos', 'DESC')
            ->findAll();
    }
}
