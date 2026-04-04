<?php

namespace App\Models;

use CodeIgniter\Model;


class EstadisticasModel extends Model
{
    public function totalGoles()
    {
        $db = \Config\Database::connect();
        $res = $db->table('equipos')
            ->selectSum('goles_favor') // ajusta el nombre si es distinto
            ->get()
            ->getRow();
        return $res->goles_favor ?? 0;
    }

    public function golesPorFechaGlobal()
{
    $db = \Config\Database::connect();
    $seriesTablas = ['principal', 'segunda', 'juvenil', 'senior', 'damas', 'infantil', 'sub14', 'sub12'];
    $golesPorFecha = [];

    foreach ($seriesTablas as $tabla) {
        $resultados = $db->table($tabla)
            ->select('fecha_id, COALESCE(goles_local,0) + COALESCE(goles_visita,0) AS goles')
            ->get()
            ->getResultArray();

        foreach ($resultados as $row) {
            $fechaId = $row['fecha_id'];
            $goles = (int) $row['goles'];

            if (!isset($golesPorFecha[$fechaId])) {
                $golesPorFecha[$fechaId] = 0;
            }

            $golesPorFecha[$fechaId] += $goles;
        }
    }

    // Obtener fechas reales
    $fechas = $db->table('fechas')->select('id, fecha')->get()->getResultArray();
    $mapaFechas = [];
    foreach ($fechas as $f) {
        $mapaFechas[$f['id']] = $f['fecha'];
    }

    // Formatear salida
    $salida = [];
    foreach ($golesPorFecha as $fechaId => $total_goles) {
        $salida[] = [
            'fecha' => $mapaFechas[$fechaId] ?? 'Sin fecha',
            'total_goles' => $total_goles
        ];
    }

    // Ordenar por fecha
    usort($salida, fn($a, $b) => strcmp($a['fecha'], $b['fecha']));

    return $salida;
}

    public function partidosEstimadosPorFechasJugadas()
{
    $db = \Config\Database::connect();

    // Paso 1: contar fechas jugadas
    $fechasJugadas = $db->table('fechas')->where('estado', 'jugado')->countAllResults();

    // Paso 2 y 3: calcular partidos estimados
    $partidosPorSerie = 7;
    $seriesActivas = 8;

    $totalEstimado = $fechasJugadas * $partidosPorSerie * $seriesActivas;

    return $totalEstimado;
}


    public function partidosPendientes()
    {
        $db = \Config\Database::connect();
        return $db->table('partidos')->where('estado', 'pendiente')->countAllResults();
    }

    public function totalEquipos()
    {
        $db = \Config\Database::connect();
        return $db->table('equipos')->countAllResults();
    }
}
