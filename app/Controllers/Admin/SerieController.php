<?php

namespace App\Controllers\Admin;

class SerieController extends BaseAdminController
{
    protected $modelName = 'App\Models\SerieModel';

    public function index()
    {
        return $this->render('admin/series', [
            'titulo' => 'Gestión de Series',
            'series' => $this->model->findAll()
        ]);
    }

    public function crear()
    {
        return $this->render('admin/form_crear_serie', [
            'titulo' => 'Crear Serie'
        ]);
    }

    public function guardar()
    {
        // La ruta en Routes es 'admin/series', NO '/admin/series'
        return $this->_guardarGenerico('admin/series');
    }

    public function editar($id)
    {
        return $this->_editarGenerico($id, 'admin/form_editar_serie', [
            'titulo' => 'Editar Serie'
        ]);
    }

    public function actualizar($id)
    {
        return $this->_actualizarGenerico($id, 'admin/series');
    }

    public function eliminar($id = null)
    {
        return $this->_eliminarGenerico($id, 'admin/series');
    }
}