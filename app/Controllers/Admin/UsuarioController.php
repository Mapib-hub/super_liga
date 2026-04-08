<?php

namespace App\Controllers\Admin;

use CodeIgniter\Shield\Models\UserModel;
use App\Models\InstitucionModel;

class UsuarioController extends BaseAdminController
{
    protected $modelName = 'CodeIgniter\Shield\Models\UserModel';

    public function index(): string
    {
        $usuarios = $this->model->findAll();
        foreach ($usuarios as &$usuario) {
            $usuario->groups = $usuario->getGroups();
        }

        return $this->render('admin/list_usuarios', [
            'titulo'   => 'Gestión de Usuarios',
            'usuarios' => $usuarios
        ]);
    }

    public function crear(): string
    {
        $instModel = new InstitucionModel();
        return $this->render('admin/form_crear_usuario', [
            'titulo'        => 'Nuevo Usuario',
            'instituciones' => $instModel->findAll(),
            'roles'         => ['admin', 'delegado', 'usuario'] // Roles definidos en Shield
        ]);
    }

    public function guardar()
    {
        $db = db_connect();
        $post = $this->request->getPost();

        // 1. Validar si el email ya existe en Shield
        $emailExiste = $db->table('auth_identities')
            ->where('type', 'email_password')
            ->where('secret', $post['email'])
            ->get()->getRow();

        if ($emailExiste) {
            return $this->_redireccionarHtmx('admin/usuarios', 'El correo ya está registrado', 'error');
        }

        // 2. Crear el usuario (Entidad de Shield)
        $user = new \CodeIgniter\Shield\Entities\User([
            'username'       => $post['username'],
            'active'         => 1,
            'institucion_id' => $post['institucion_id'] ?: null
        ]);
        // dd($user);
        $this->model->save($user);
        $userId = $this->model->getInsertID();

        // 3. Crear identidad (Email/Password)
        $hasher = service('passwords');
        $db->table('auth_identities')->insert([
            'user_id' => $userId,
            'type'    => 'email_password',
            'secret'  => $post['email'],
            'secret2' => $hasher->hash($post['password']),
        ]);

        // 4. Asignar Rol
        $user = $this->model->find($userId);
        $user->addGroup($post['rol']);

        return $this->_redireccionarHtmx('admin/usuarios', 'Usuario creado correctamente');
    }
    public function editar($id = null)
    {
        $userModel = new \CodeIgniter\Shield\Models\UserModel();
        $instModel = new \App\Models\InstitucionModel();

        // 1. Buscar al usuario
        $usuario = $userModel->find($id);
        if (!$usuario) {
            return $this->_redireccionarHtmx('admin/usuarios', 'Usuario no encontrado', 'error');
        }

        // 2. Obtener el correo desde auth_identities (Shield guarda el mail como 'secret')
        $db = db_connect();
        $identity = $db->table('auth_identities')
            ->where('user_id', $id)
            ->where('type', 'email_password')
            ->get()
            ->getRow();

        return $this->render('admin/form_editar_usuario', [
            'titulo'        => 'Editar Usuario',
            'usuario'       => $usuario,
            'email'         => $identity ? $identity->secret : '',
            'groups'        => $usuario->getGroups(),
            'instituciones' => $instModel->findAll()
        ]);
    }
    public function actualizar($id = null)
    {
        $db = db_connect();
        $post = $this->request->getPost();
        $id = $post['id']; // Asegúrate de que el hidden input se llame 'id'
        // VALIDACIÓN: ¿El email lo tiene ALGUIEN MÁS?
        $emailOcupado = $db->table('auth_identities')
            ->where('secret', $post['email'])
            ->where('type', 'email_password')
            ->where('user_id !=', $id) // Que no sea el mío
            ->get()->getRow();

        if ($emailOcupado) {
            return $this->_redireccionarHtmx('admin/usuarios', "El correo ya pertenece a otro usuario", 'error');
        }
        $user = $this->model->find($id);
        if (!$user) {
            return $this->_redireccionarHtmx('admin/usuarios', 'Usuario no existe', 'error');
        }

        // 1. Actualizar datos básicos en tabla 'users'
        $user->username = $post['nombre_usuario'];
        $user->institucion_id = $post['institucion_id'] ?: null;
        $user->active = isset($post['activo']) ? 1 : 0;

        $this->model->save($user);

        // 2. Actualizar Email y Password en 'auth_identities'
        $identityData = [
            'secret' => $post['email']
        ];

        // Solo si escribió algo en el campo password, lo hasheamos y actualizamos
        if (!empty($post['password'])) {
            $hasher = service('passwords');
            $identityData['secret2'] = $hasher->hash($post['password']);
        }

        $db->table('auth_identities')
            ->where('user_id', $id)
            ->where('type', 'email_password')
            ->update($identityData);

        // 3. Sincronizar Rol (Elimina los viejos y pone el nuevo)
        $user->syncGroups($post['rol']);

        return $this->_redireccionarHtmx('admin/usuarios', 'Usuario actualizado correctamente');
    }
    public function eliminar($id = null)
    {
        if ($this->model->delete($id, true)) { // true para borrado permanente si prefieres
            return $this->_redireccionarHtmx('admin/usuarios', 'Usuario eliminado');
        }
        return $this->_redireccionarHtmx('admin/usuarios', 'Error al eliminar', 'error');
    }
}
