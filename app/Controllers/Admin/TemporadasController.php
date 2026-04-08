<?php

namespace App\Controllers\Admin;

class TemporadasController extends BaseAdminController
{
    protected $modelName = 'App\Models\TemporadaModel';

    public function index()
    {
        // Solo enviamos la ruta relativa, sin file_get_contents para no romper nada
        return $this->render('admin/list_temporadas', [
            'titulo'     => 'Gestión de Temporadas',
            'temporadas' => $this->model->orderBy('id', 'DESC')->findAll(),
            'style_file' => 'assets/css/temporadas_admin.css'
        ]);
    }

    public function crear()
    {
        return $this->render('admin/form_crear_temporada', [
            'titulo' => 'Nueva Temporada'
        ]);
    }

    public function guardar()
    {
        $data = $this->getRequestData();

        try {
            // 1. Insertamos la temporada básica
            $id = $this->model->insert([
                'nombre_temporada' => $data['nombre_temporada'],
                'actual'           => 0
            ]);

            if ($id) {
                // 2. Si marcaron "actual", usamos tu método del modelo
                if (isset($data['actual']) && ($data['actual'] == 1 || $data['actual'] == "true")) {
                    $this->model->setActual($id);
                }

                return $this->_redireccionarHtmx('admin/temporadas', 'Temporada creada correctamente.');
            }

            return $this->_redireccionarHtmx('admin/temporadas', 'No se pudo guardar.', 'error');
        } catch (\Exception $e) {
            return $this->_redireccionarHtmx('admin/temporadas', 'Error: ' . $e->getMessage(), 'error');
        }
    }

    public function editar($id = null)
    {
        // 1. Buscamos la temporada por su ID
        $temporada = $this->model->find($id);

        // 2. Validamos si existe (por seguridad)
        if (!$temporada) {
            return "Error: La temporada no existe.";
        }

        // 3. Enviamos la variable EXACTAMENTE con el nombre 'temporada'
        return $this->render('admin/form_editar_temporada', [
            'titulo'    => 'Editar Temporada',
            'temporada' => $temporada // <--- Esta es la que te falta
        ]);
    }

    public function actualizar($id)
    {
        $data = $this->getRequestData();

        try {
            // Actualizamos nombre
            $this->model->update($id, [
                'nombre_temporada' => $data['nombre_temporada']
            ]);

            // Si se marcó como actual
            if (isset($data['actual']) && ($data['actual'] == 1 || $data['actual'] == "true")) {
                $this->model->setActual($id);
            }

            return $this->_redireccionarHtmx('admin/temporadas', 'Temporada actualizada.');
        } catch (\Exception $e) {
            return $this->_redireccionarHtmx('admin/temporadas', 'Error: ' . $e->getMessage(), 'error');
        }
    }

    // Método especial para activar desde la lista (un rayito o switch)
    public function activar($id)
    {
        if ($this->model->setActual($id)) {
            return $this->_redireccionarHtmx('admin/temporadas', 'Temporada activa cambiada.');
        }
        return $this->_redireccionarHtmx('admin/temporadas', 'Error al activar.', 'error');
    }

    public function eliminar($id = null)
    {
        return $this->_eliminarGenerico($id, 'admin/temporadas');
    }
}