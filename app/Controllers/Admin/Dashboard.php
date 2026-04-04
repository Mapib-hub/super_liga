<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\PartidoModel;
use App\Models\FechaModel;

class Dashboard extends BaseController
{
    public function index(): string
    {
        // La temporada ya viene cargada desde el initController del BaseController
        $idTemp = $this->temporada['id'] ?? null;

        if (!$idTemp) {
            return $this->render('admin/dashboard', ['error' => 'No hay temporada activa']);
        }

        $partidoModel = new PartidoModel();
        $fechaModel   = new FechaModel();

        $fechstats     = $fechaModel->getConteosGenerales($idTemp);
        $stats         = $partidoModel->getConteosGenerales($idTemp);
        $desgloseGoles = $partidoModel->getGolesPorSerie($idTemp);

        $data = [
            'totalFechas'     => $fechstats['totalfechas'],
            'fechasJugadas'   => $fechstats['fechasjugadas'],
            'totalPartidos'   => $stats['total'],
            'partidosJugados' => $stats['jugados'],
            'totalGoles'      => array_sum(array_column($desgloseGoles, 'goles')),
            'porcentaje'      => ($fechstats['totalfechas'] > 0) ? round(($fechstats['fechasjugadas'] / $fechstats['totalfechas']) * 100) : 0,
            'desgloseGoles'   => $desgloseGoles,
        ];

        // Usamos el método render heredado del BaseController
        return $this->render('admin/dashboard', $data);
    }
}