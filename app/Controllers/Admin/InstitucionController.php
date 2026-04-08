<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

// IMPORTANTE: Extendemos de BaseAdminController para heredar el CRUD genérico
class InstitucionController extends BaseAdminController
{
    protected $modelName = 'App\Models\InstitucionModel';

    /**
     * Listado de instituciones
     */
    public function index(): string
    {
        return $this->render('admin/list_instituciones', [
            'titulo'        => 'Gestión de Instituciones',
            'instituciones' => $this->model->findAll(),
        ]);
    }

    /**
     * Formulario de creación
     */
    public function crear(): string
    {
        return $this->render('admin/form_institucion', [
            'titulo' => 'Registrar Nueva Institución',
        ]);
    }

    /**
     * Guardar nueva institución con Logo
     */
    public function guardar()
    {
        $file = $this->request->getFile('logo');

        // Validación de tamaño (Server side)
        if ($file && $file->getError() === 1) {
            return redirect()->to(base_url('admin/instituciones'))->with('error', 'La imagen es demasiado grande.');
        }

        // Subir archivo si existe
        $logoPath = $this->_subirArchivo($file, 'logos');

        $datos = $this->request->getPost();
        if ($logoPath) {
            $datos['logo_path'] = 'uploads/logos/' . $logoPath;
        }

        if ($this->model->insert($datos)) {
            return redirect()->to(base_url('admin/instituciones'))->with('success', 'Institución creada correctamente.');
        }

        return redirect()->to(base_url('admin/instituciones'))->with('error', 'Error al guardar.');
    }

    /**
     * Formulario de edición (Estandarizado)
     */
    public function editar($id)
    {
        // El Padre cargará la variable como $reg (según vimos en tu debug)
        return $this->_editarGenerico($id, 'admin/form_institucion_editar', [
            'titulo' => 'Editar Institución'
        ]);
    }

    /**
     * Actualizar institución (Manejo de Logo viejo)
     */
    public function actualizar(int $id)
    {
        $institucion = $this->model->find($id);
        if (!$institucion) {
            return redirect()->to(base_url('admin/instituciones'))->with('error', 'Registro no encontrado.');
        }

        $data = $this->request->getPost();
        $file = $this->request->getFile('logo');

        if ($file && $file->isValid() && !$file->hasMoved()) {
            $nuevoLogoPath = $this->_subirArchivo($file, 'logos');
            if ($nuevoLogoPath) {
                $data['logo_path'] = 'uploads/logos/' . $nuevoLogoPath;
                if (!empty($institucion['logo_path'])) {
                    $rutaVieja = FCPATH . $institucion['logo_path'];
                    if (file_exists($rutaVieja)) @unlink($rutaVieja);
                }
            }
        }

        if ($this->model->update($id, $data)) {
            return redirect()->to(base_url('admin/instituciones'))->with('success', 'Institución actualizada.');
        }

        return redirect()->to(base_url('admin/instituciones'))->with('error', 'Error al actualizar.');
    }

    /**
     * Eliminar (Manejo de archivo físico)
     */
    public function eliminar($id = null)
    {
        $inst = $this->model->find($id);
        if ($inst && !empty($inst['logo_path'])) {
            $ruta = FCPATH . $inst['logo_path'];
            if (file_exists($ruta)) {
                @unlink($ruta);
            }
        }

        return $this->_eliminarGenerico($id, 'admin/instituciones');
    }
}
