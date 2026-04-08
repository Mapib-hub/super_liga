<?php

namespace App\Controllers\Admin;

use App\Models\JugadorModel;
use App\Models\InstitucionModel;

class JugadorController extends BaseAdminController
{
    protected $modelName = 'App\Models\JugadorModel';

    public function index(): string
    {
        // El controlador ya no sabe de JOINs ni de tablas, solo pide los datos
        $jugadores = $this->model->obtenerTodosConInstitucion();

        return $this->render('admin/list_jugadores', [
            'titulo'    => 'Gestión de Jugadores',
            'jugadores' => $jugadores
        ]);
    }

    public function crear(): string
    {
        $instModel = new InstitucionModel();
        return $this->render('admin/form_crear_jugador', [
            'titulo'        => 'Nuevo Jugador',
            'instituciones' => $instModel->findAll()
        ]);
    }

    public function guardar()
    {
        $data = $this->request->getPost();

        // Validar RUT/DNI único
        if ($this->model->where('rut_dni', $data['rut_dni'])->first()) {
            return $this->_redireccionarHtmx('admin/jugadores', 'Ya existe un jugador con ese RUT/DNI', 'error');
        }

        // Manejo de la foto (Copiamos tu lógica de validación)
        $file = $this->request->getFile('foto');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $newName = $file->getRandomName();
            $file->move(FCPATH . 'uploads/jugadores', $newName);
            $data['foto_path'] = $newName;
        }

        $data['activo'] = isset($data['activo']) ? 1 : 0;

        if ($this->model->insert($data)) {
            return $this->_redireccionarHtmx('admin/jugadores', 'Jugador registrado con éxito');
        }

        return $this->_redireccionarHtmx('admin/jugadores', 'Error al guardar datos', 'error');
    }

    public function editar($id = null)
    {
        $jugador = $this->model->find($id);
        if (!$jugador) {
            return $this->_redireccionarHtmx('admin/jugadores', 'Jugador no encontrado', 'error');
        }

        $instModel = new InstitucionModel();
        return $this->render('admin/form_editar_jugador', [
            'titulo'        => 'Editar Jugador',
            'jugador'       => $jugador,
            'instituciones' => $instModel->findAll()
        ]);
    }

    public function actualizar($id = null)
    {
        $id = $this->request->getPost('id'); // ID desde el form
        $jugador = $this->model->find($id);

        $data = $this->request->getPost();
        $data['activo'] = isset($data['activo']) ? 1 : 0;

        // Validar RUT si cambió
        if ($data['rut_dni'] !== $jugador['rut_dni']) {
            if ($this->model->where('rut_dni', $data['rut_dni'])->where('id !=', $id)->first()) {
                return $this->_redireccionarHtmx('admin/jugadores', 'El RUT ya pertenece a otro jugador', 'error');
            }
        }

        // Nueva foto
        $file = $this->request->getFile('foto');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $newName = $file->getRandomName();
            $file->move(FCPATH . 'uploads/jugadores', $newName);
            $data['foto_path'] = $newName;

            // Borrar foto vieja
            if (!empty($jugador['foto_path']) && file_exists(FCPATH . 'uploads/jugadores/' . $jugador['foto_path'])) {
                unlink(FCPATH . 'uploads/jugadores/' . $jugador['foto_path']);
            }
        }

        if ($this->model->update($id, $data)) {
            return $this->_redireccionarHtmx('admin/jugadores', 'Jugador actualizado correctamente');
        }

        return $this->_redireccionarHtmx('admin/jugadores', 'Error al actualizar', 'error');
    }

    public function eliminar($id = null)
    {
        $jugador = $this->model->find($id);
        if ($jugador) {
            // Borramos la foto física antes de eliminar el registro
            if (!empty($jugador['foto_path']) && file_exists(FCPATH . 'uploads/jugadores/' . $jugador['foto_path'])) {
                unlink(FCPATH . 'uploads/jugadores/' . $jugador['foto_path']);
            }
            $this->model->delete($id);
            return $this->_redireccionarHtmx('admin/jugadores', 'Jugador eliminado');
        }
        return $this->_redireccionarHtmx('admin/jugadores', 'No se pudo eliminar', 'error');
    }
}
