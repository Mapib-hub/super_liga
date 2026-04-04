<?php

declare(strict_types=1);

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;
use App\Models\TemporadaModel;

abstract class BaseController extends Controller
{
    protected $request;
    protected $helpers = ['auth', 'url', 'form', 'text']; // 'form' es vital para old()
    protected array $temporada;

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);

        $tempModel = new TemporadaModel();
        $this->temporada = $tempModel->where('actual', 1)->first() ?? [];
        \Config\Services::renderer()->setData(['tempActual' => $this->temporada], 'raw');
    }

    protected function render(string $view, array $data = []): string
    {
        if ($this->request->hasHeader('HX-Request')) {
            return view($view, $data);
        }

        return view('admin/layout', [
            'view' => $view,
            'data' => $data
        ]);
    }

    protected function _subirArchivo($file, string $folder = 'general'): ?string
    {
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $newName = $file->getRandomName();
            $targetPath = 'uploads/' . $folder . '/';

            if (!is_dir(FCPATH . $targetPath)) {
                mkdir(FCPATH . $targetPath, 0777, true);
            }

            $file->move(FCPATH . $targetPath, $newName);
            return $newName;
        }
        return null;
    }

    protected function _redireccionarHtmx(string $url, string $mensaje, string $tipo = 'success')
    {
        if ($this->request->hasHeader('HX-Request')) {
            // Configuramos la redirección de HTMX como un objeto JSON
            $location = json_encode([
                'path'   => $url,
                'target' => '#main-content', // Le decimos exactamente dónde inyectar el listado
                'push'   => true             // Esto actualiza la URL en la barra del navegador
            ]);

            return $this->response
                ->setHeader('HX-Location', $location)
                ->setHeader('HX-Trigger', json_encode([
                    'swal:fire' => [
                        'title' => ($tipo === 'success') ? '¡Logrado!' : 'Atención',
                        'text'  => $mensaje,
                        'icon'  => $tipo
                    ]
                ]));
        }

        return redirect()->to($url)->with($tipo, $mensaje);
    }
}