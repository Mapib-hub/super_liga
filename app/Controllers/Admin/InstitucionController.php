<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\InstitucionModel;

class InstitucionController extends BaseController
{
    protected $institucionModel;

    public function __construct()
    {
        $this->institucionModel = new InstitucionModel();
    }

    /**
     * Listado de instituciones
     */
    public function index(): string
    {
        $data = [
            'titulo'        => 'Gestión de Instituciones',
            'instituciones' => $this->institucionModel->findAll(),
        ];

        return $this->render('admin/list_instituciones', $data);
    }

    /**
     * Formulario de creación
     */
    public function crear(): string
    {
        $data = [
            'titulo' => 'Registrar Nueva Institución',
        ];

        return $this->render('admin/form_institucion', $data);
    }

    /**
     * Guardar nueva institución
     */
    public function guardar()
    {
        $file = $this->request->getFile('logo');

        // 1. Error de Servidor (PHP.ini)
        if ($file && $file->getError() === 1) {
            return $this->_redireccionarHtmx('/admin/instituciones/crear', 'La imagen es demasiado grande para el servidor.', 'error');
        }

        // 2. Reglas de Validación
        $reglas = [
            'nombre'         => 'required|min_length[3]|is_unique[instituciones.nombre]',
            'email_contacto' => 'required|valid_email|is_unique[instituciones.email_contacto]',
            'logo'           => [
                'label' => 'Logo del Club',
                'rules' => 'uploaded[logo]|is_image[logo]|mime_in[logo,image/jpg,image/jpeg,image/png,image/gif,image/webp]|max_size[logo,2048]',
            ],
        ];

        if (!$this->validate($reglas)) {
            // Disparar SweetAlert
            $this->response->setHeader('HX-Trigger', json_encode([
                'swal:fire' => [
                    'title' => 'Datos inválidos',
                    'text'  => 'Revisa los campos marcados en el formulario.',
                    'icon'  => 'warning'
                ]
            ]));

            // Persistir inputs para old()
            session()->setFlashdata('_ci_old_input', ['post' => $this->request->getPost()]);

            return $this->render('admin/form_institucion', [
                'validation' => $this->validator,
                'titulo'     => 'Corregir Errores'
            ]);
        }

        // 3. Procesar y Guardar
        $logoPath = $this->_subirArchivo($file, 'logos');
        $datos = $this->request->getPost();
        $datos['logo_path'] = $logoPath;

        $this->institucionModel->insert($datos);

        return $this->_redireccionarHtmx('/admin/instituciones', 'Institución creada correctamente.');
    }

    /**
     * Formulario de edición
     */
    public function editar($id)
    {
        $institucion = $this->institucionModel->find($id);

        if (!$institucion) {
            return $this->_redireccionarHtmx('admin/instituciones', 'Institución no encontrada', 'error');
        }

        $data = [
            'inst'   => $institucion,
            'titulo' => 'Editar Institución'
        ];

        return $this->render('admin/form_institucion_editar', $data);
    }

    /**
     * Actualizar institución existente
     */
    public function actualizar(int $id)
    {
        $institucion = $this->institucionModel->find($id);

        if (!$institucion) {
            return $this->_redireccionarHtmx('admin/instituciones', 'Registro no encontrado.', 'error');
        }

        // 1. Validar (excluyendo el ID actual en el unique)
        $reglas = [
            'nombre'         => "required|min_length[3]|is_unique[instituciones.nombre,id,{$id}]",
            'email_contacto' => "required|valid_email|is_unique[instituciones.email_contacto,id,{$id}]",
            'logo'           => 'if_exist|is_image[logo]|max_size[logo,2048]|ext_in[logo,png,jpg,jpeg,webp]',
        ];

        if (!$this->validate($reglas)) {
            // Persistir inputs para que old() funcione en la edición
            session()->setFlashdata('_ci_old_input', ['post' => $this->request->getPost()]);

            return $this->render('admin/form_institucion_editar', [
                'inst'       => $institucion,
                'validation' => $this->validator,
                'titulo'     => 'Corregir Errores'
            ]);
        }

        // 2. Datos básicos
        $data = $this->request->getPost();
        $file = $this->request->getFile('logo');

        // 3. Lógica de imagen nueva
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $nuevoLogoPath = $this->_subirArchivo($file, 'logos');

            if ($nuevoLogoPath) {
                $data['logo_path'] = $nuevoLogoPath;

                // Borrar logo físico anterior
                if (!empty($institucion['logo_path'])) {
                    $rutaVieja = FCPATH . $institucion['logo_path'];
                    if (file_exists($rutaVieja)) {
                        @unlink($rutaVieja);
                    }
                }
            }
        }

        // 4. Actualizar
        $this->institucionModel->update($id, $data);

        return $this->_redireccionarHtmx('admin/instituciones', 'Institución actualizada con éxito.');
    }

    /**
     * Eliminar institución y su logo
     */
    public function eliminar($id)
    {
        $inst = $this->institucionModel->find($id);

        if ($inst) {
            // Borrar archivo físico del logo
            if (!empty($inst['logo_path'])) {
                $ruta = FCPATH . $inst['logo_path'];
                if (file_exists($ruta)) {
                    @unlink($ruta);
                }
            }
            // Borrar de la BD
            $this->institucionModel->delete($id);
        }

        return $this->_redireccionarHtmx('/admin/instituciones', 'Registro eliminado correctamente.');
    }
}