<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use CodeIgniter\API\ResponseTrait;
use App\Models\NoticiaImagenModel;
use App\Models\NoticiaModel;

// Importaciones de Cloudinary (Ahora que ya están instaladas)
use Cloudinary\Configuration\Configuration;
use Cloudinary\Cloudinary;

class NoticiasImagenesController extends BaseController
{
    use ResponseTrait;

    protected $imagenesModel;

    public function __construct()
    {
        helper(['form', 'url']);
        $this->imagenesModel = new NoticiaImagenModel();
    }

    public function index($id)
    {
        $imagenes = $this->imagenesModel->where('noticia_id', $id)->findAll();
        return $this->respond($imagenes, 200);
    }

    public function subir($noticia_id)
    {
        $noticiaModel = new NoticiaModel();
        if (!$noticiaModel->find($noticia_id)) {
            return $this->failNotFound('La noticia no existe.');
        }

        $files = $this->request->getFiles();
        if (!isset($files['imagenes'])) {
            return $this->failValidationErrors(['imagenes' => 'No hay imágenes para subir.']);
        }

        // --- CAMBIO AQUÍ: Configuración directa en la instancia ---
        try {
            $cloudinary = new Cloudinary([
                'cloud' => [
                    'cloud_name' => 'dhrncojdr',
                    'api_key'    => '822914238526977',
                    'api_secret' => 'cpMy3fJgSkEUBTBxxoVF5R0JIZw'
                ],
                'url' => [
                    'secure' => true
                ]
            ]);

            $subidos = 0;
            $errores = [];

            foreach ($files['imagenes'] as $img) {
                if ($img->isValid() && !$img->hasMoved()) {
                    // Subida
                    $resultado = $cloudinary->uploadApi()->upload($img->getTempName(), [
                        'folder' => 'noticias_galeria',
                        'quality' => 'auto',
                        'fetch_format' => 'auto'
                    ]);

                    // Guardar en DB
                    $this->imagenesModel->insert([
                        'noticia_id'  => $noticia_id,
                        'imagen_path' => $resultado['secure_url']
                    ]);
                    $subidos++;
                }
            }

            return $this->_redireccionarHtmx("admin/noticias/editar/$noticia_id", 'Galería actualizada');
        } catch (\Exception $e) {
            return $this->_redireccionarHtmx("admin/noticias/editar/$noticia_id", 'Error Cloudinary: ' . $e->getMessage(), 'error');
        }
    }
    public function eliminar($id, $noticia_id)
    {
        // Solo borramos el registro de la DB por ahora
        $this->imagenesModel->delete($id);
        return $this->_redireccionarHtmx("admin/noticias/editar/$noticia_id", 'Imagen eliminada');
    }
}
