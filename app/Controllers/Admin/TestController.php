<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class TestController extends BaseController
{
    public function testToast()
    {
        if ($this->request->hasHeader('HX-Request')) {
            return $this->response
                ->setHeader('HXToaster-Body', '¡Prueba exitosa! El toast funciona.')
                ->setHeader('HXToaster-Type', 'success');
        }

        return redirect()->with($tipo, $mensaje);
    }
}
