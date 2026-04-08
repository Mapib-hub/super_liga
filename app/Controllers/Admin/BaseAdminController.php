<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class BaseAdminController extends BaseController
{
    protected $model;

    public function __construct()
    {
        if (property_exists($this, 'modelName')) {
            $this->model = model($this->modelName);
        }
    }

    protected function getRequestData()
    {
        $data = $this->request->getPost();
        return empty($data) ? $this->request->getJSON(true) : $data;
    }

    /**
     * Esta es la pieza que faltaba: Maneja el SweetAlert y la redirección HTMX
     */
    protected function _redireccionarHtmx($url, $mensaje, $tipo = 'success')
    {
        // Enviamos la cabecera para que SweetAlert salte en el navegador
        $this->response->setHeader('HX-Trigger', json_encode([
            'swal:fire' => [
                'title' => ($tipo === 'success' ? '¡Hecho!' : 'Atención'),
                'text'  => $mensaje,
                'icon'  => $tipo
            ]
        ]));

        // Redireccionamos usando base_url() para evitar el error 404 de rutas duplicadas
        return redirect()->to(base_url($url));
    }

    // --- ACCIONES GENÉRICAS CON MANEJO DE ERRORES ---

    protected function _guardarGenerico($urlRetorno)
    {
        try {
            if ($this->model->insert($this->getRequestData())) {
                return $this->_redireccionarHtmx($urlRetorno, 'Registro creado con éxito.');
            }

            $errores = implode('<br>', $this->model->errors());
            return $this->_redireccionarHtmx($urlRetorno, 'Error: ' . $errores, 'error');
        } catch (\Exception $e) {
            // Si el modelo falla (ej: error en el slug), esto te avisará
            return $this->_redireccionarHtmx($urlRetorno, 'Fallo de Sistema: ' . $e->getMessage(), 'error');
        }
    }

    protected function _editarGenerico($id, $vista, $extraData = [])
    {
        $registro = $this->model->find($id);
        if (!$registro) {
            return "Registro no encontrado";
        }

        $data = array_merge(['reg' => $registro], $extraData);
        // Usamos render() para que cargue dentro del #main-content con el layout
        return $this->render($vista, $data);
    }

    protected function _actualizarGenerico($id, $urlRetorno)
    {
        try {
            if ($this->model->update($id, $this->getRequestData())) {
                return $this->_redireccionarHtmx($urlRetorno, 'Actualizado correctamente.');
            }

            $errores = implode('<br>', $this->model->errors());
            return $this->_redireccionarHtmx($urlRetorno, 'Error: ' . $errores, 'error');
        } catch (\Exception $e) {
            return $this->_redireccionarHtmx($urlRetorno, 'Fallo de Sistema: ' . $e->getMessage(), 'error');
        }
    }

    protected function _eliminarGenerico($id, $urlRetorno)
    {
        if ($this->model->delete($id)) {
            return $this->_redireccionarHtmx($urlRetorno, 'Eliminado correctamente.');
        }
        return $this->_redireccionarHtmx($urlRetorno, 'Error al eliminar.', 'error');
    }
}
