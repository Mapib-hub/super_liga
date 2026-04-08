<?php

namespace App\Controllers\Admin;

use App\Models\NoticiaModel;
use App\Models\NoticiaImagenModel;
use App\Models\InstitucionModel; // Para poder elegir la institución en el formulario

class NoticiaController extends BaseAdminController
{
    protected $modelName = 'App\Models\NoticiaModel';

    /**
     * Listado de noticias para el panel
     */
    public function index(): string
    {
        return $this->render('admin/list_noticias', [
            'titulo'   => 'Gestión de Noticias',
            'noticias' => $this->model->orderBy('id', 'DESC')->findAll(),
        ]);
    }

    /**
     * Formulario para crear noticia
     */
    public function crear(): string
    {
        $instModel = new InstitucionModel();
        return $this->render('admin/form_crear_noticia', [
            'titulo'       => 'Nueva Noticia',
            'instituciones' => $instModel->findAll(), // Para el select de institución
        ]);
    }

    /**
     * Guardar noticia con imagen
     */
    public function guardar()
    {
        $data = $this->request->getPost();
        $file = $this->request->getFile('imagen');

        // Usamos tu lógica de BaseController para subir el archivo
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $newName = $this->_subirArchivo($file, 'noticias');
            if ($newName) {
                $data['imagen'] = $newName;
            }
        }

        $data['fecha_creacion'] = date('Y-m-d H:i:s');

        if ($this->model->insert($data)) {
            return $this->_redireccionarHtmx('admin/noticias', 'Noticia publicada con éxito');
        }

        return $this->_redireccionarHtmx('admin/noticias', 'Error al guardar noticia', 'error');
    }

    /**
     * Formulario de edición
     */
    public function editar($id = null)
    {
        $noticia = $this->model->find($id);

        if (!$noticia) {
            return $this->_redireccionarHtmx('admin/noticias', 'Noticia no encontrada', 'error');
        }

        // --- LAS ÚNICAS DOS LÍNEAS QUE NECESITAS AÑADIR ---
        $imgModel = new \App\Models\NoticiaImagenModel();
        $imagenesExtra = $imgModel->where('noticia_id', $id)->findAll();
        // ------------------------------------------------

        return $this->render('admin/form_editar_noticia', [
            'titulo' => 'Editar Noticia',
            'reg'    => $noticia,
            'instituciones' => (new \App\Models\InstitucionModel())->findAll(),
            'imagenesExtra' => $imagenesExtra // Pasar la variable aquí
        ]);
    }
    /**
     * Actualizar noticia (Manejo de imagen vieja)
     */
    public function actualizar($id = null)
    {
        $noticia = $this->model->find($id);
        if (!$noticia) {
            return $this->_redireccionarHtmx('admin/noticias', 'Noticia no encontrada', 'error');
        }

        $data = $this->request->getPost();
        $file = $this->request->getFile('imagen');

        if ($file && $file->isValid() && !$file->hasMoved()) {
            $newName = $this->_subirArchivo($file, 'noticias');
            if ($newName) {
                $data['imagen'] = $newName;

                // Borrar imagen anterior si existe
                if (!empty($noticia['imagen'])) {
                    $oldFile = FCPATH . 'uploads/noticias/' . $noticia['imagen'];
                    if (file_exists($oldFile)) @unlink($oldFile);
                }
            }
        }

        if ($this->model->update($id, $data)) {
            return $this->_redireccionarHtmx('admin/noticias', 'Noticia actualizada');
        }

        return $this->_redireccionarHtmx('admin/noticias', 'Error al actualizar', 'error');
    }

    /**
     * Eliminar noticia y su archivo físico
     */
    public function eliminar($id = null)
    {
        $noticia = $this->model->find($id);

        if ($noticia && !empty($noticia['imagen'])) {
            $ruta = FCPATH . 'uploads/noticias/' . $noticia['imagen'];
            if (file_exists($ruta)) @unlink($ruta);
        }

        if ($this->model->delete($id)) {
            return $this->_redireccionarHtmx('admin/noticias', 'Noticia eliminada');
        }

        return $this->_redireccionarHtmx('admin/noticias', 'No se pudo eliminar', 'error');
    }
}
