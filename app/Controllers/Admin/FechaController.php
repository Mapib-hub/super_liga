<?php

namespace App\Controllers\Admin;

// 1. Cambiamos el Padre para usar lo genérico
class FechaController extends BaseAdminController
{
    protected $modelName = 'App\Models\FechaModel';

    public function index()
    {
        // El listado de fechas es especial por el JOIN y el WHERE de temporada actual
        $fechas = $this->model->select('fechas.*, temporadas.nombre_temporada')
            ->join('temporadas', 'temporadas.id = fechas.temporada_id', 'left')
            ->where('temporadas.actual', 1)
            ->orderBy('estado', 'pendiente')
            ->orderBy('fechas.id', 'ASC')
            ->findAll();

        return $this->render('admin/list_fechas', [
            'titulo' => 'Gestión de Fechas',
            'fechas' => $fechas
        ]);
    }

    public function crear()
    {
        $tempModel = new \App\Models\TemporadaModel();
        return $this->render('admin/form_crear_fechas', [
            'titulo'     => 'Nueva Fecha',
            'temporadas' => $tempModel->orderBy('actual', 'DESC')->findAll()
        ]);
    }

    public function guardar()
    {
        // USAMOS LO GENÉRICO: Menos código, mismos resultados
        return $this->_guardarGenerico('admin/fechas');
    }

    public function editar($id)
    {
        $tempModel = new \App\Models\TemporadaModel();
        return $this->_editarGenerico($id, 'admin/form_editar_fechas', [
            'titulo'     => 'Editar Fecha',
            'temporadas' => $tempModel->findAll()
        ]);
    }

    public function actualizar($id)
    {
        return $this->_actualizarGenerico($id, 'admin/fechas');
    }

    public function eliminar($id = null)
    {
        return $this->_eliminarGenerico($id, 'admin/fechas');
    }
}