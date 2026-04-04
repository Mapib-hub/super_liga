<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class AuthController extends BaseController
{
    /**
     * Este método decide a dónde va el usuario después del Login.
     * Es escalable: si mañana agregas el rol "Arbitro", solo sumas un caso aquí.
     */
    public function redirectByRole()
    {
        $user = auth()->user();

        if (!$user) {
            return redirect()->to('/login');
        }

        // Lógica de redirección por Grupos (Roles)
        if ($user->inGroup('superadmin', 'admin')) {
            return redirect()->to('/admin/dashboard');
        }

        if ($user->inGroup('delegado')) {
            return redirect()->to('/delegado/panel');
        }

        if ($user->inGroup('planillero')) {
            return redirect()->to('/mesa/partidos');
        }

        // Si el usuario no tiene ningún grupo asignado (Error de seguridad)
        return redirect()->to('/login')->with('error', 'Usuario sin rol asignado. Contacte al administrador.');
    }
}
