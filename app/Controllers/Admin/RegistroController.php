<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\GolModel;
use App\Models\SancionModel;
use App\Models\JugadorModel;

class RegistroController extends BaseController
{
    // Cambiamos 'registrarGol' por algo que maneje la planilla completa del partido
    public function guardarGolesPartido()
    {
        $golModel = new GolModel();
        $db = \Config\Database::connect();

        // 1. Capturamos los datos del formulario
        $partido_id = $this->request->getPost('partido_id');

        // Obtenemos el partido para sacar el serie_id y las instituciones
        $partido = $db->table('partidos')->where('id', $partido_id)->get()->getRowArray();

        if (!$partido) {
            return redirect()->back()->with('error', 'El partido no existe.');
        }

        // 2. Recogemos los arrays de los goleadores
        $jugadores_local   = $this->request->getPost('goles_local_ids');
        $cantidades_local  = $this->request->getPost('cant_local_goles');

        $jugadores_visita  = $this->request->getPost('goles_visita_ids');
        $cantidades_visita = $this->request->getPost('cant_visita_goles');

        // 3. Limpiamos los goles registrados anteriormente en este partido
        // (Asumo que tu tabla 'goles' tiene la columna 'partido_id')
        $db->table('goles')->where('partido_id', $partido_id)->delete();

        // 4. Procesar Goles LOCALES
        if (!empty($jugadores_local)) {
            foreach ($jugadores_local as $index => $jugador_id) {
                if (!empty($jugador_id)) {
                    $golModel->insert([
                        'partido_id'     => $partido_id,
                        'jugador_id'     => $jugador_id,
                        'institucion_id' => $partido['institucion_local_id'], // Usamos la columna de tu tabla
                        'serie_id'       => $partido['serie_id'],               // Automático del partido
                        'cantidad'       => $cantidades_local[$index] ?? 1
                    ]);
                }
            }
        }

        // 5. Procesar Goles VISITANTES
        if (!empty($jugadores_visita)) {
            foreach ($jugadores_visita as $index => $jugador_id) {
                if (!empty($jugador_id)) {
                    $golModel->insert([
                        'partido_id'     => $partido_id,
                        'jugador_id'     => $jugador_id,
                        'institucion_id' => $partido['institucion_visitante_id'], // Usamos la columna de tu tabla
                        'serie_id'       => $partido['serie_id'],                   // Automático del partido
                        'cantidad'       => $cantidades_visita[$index] ?? 1
                    ]);
                }
            }
        }

        return redirect()->back()->with('message', '¡Goleadores registrados y estadísticas actualizadas! ⚽');
    }

    public function registrarSancion()
    {
        // dd($this->request->getPost());
        $db = \Config\Database::connect();

        $partidoId  = $this->request->getPost('partido_id');
        $tipos      = $this->request->getPost('tipos');
        $jugadorIds = $this->request->getPost('jugador_ids');

        if (empty($jugadorIds)) {
            return redirect()->back()->with('error', 'No se seleccionaron jugadores.');
        }

        foreach ($jugadorIds as $index => $jId) {
            if (!empty($jId)) {
                // 1. Buscamos a qué institución pertenece el jugador
                $jugador = $db->table('jugadores')->where('id', $jId)->get()->getRow();
                $institucionId = $jugador ? $jugador->institucion_id : null;

                // Lógica para asegurar que el ENUM reciba lo que espera
                // trim() quita espacios, ucfirst() asegura la Mayúscula
                $tipoFinal = isset($tipos[$index]) ? ucfirst(trim($tipos[$index])) : 'Amarilla';

                // 2. Preparamos el insert
                $data = [
                    'jugador_id'     => $jId,
                    'institucion_id' => $institucionId,
                    'partido_id'     => $partidoId,
                    'tipo_tarjeta'   => $tipoFinal,
                    'observacion'    => 'Registro manual desde fixture',
                    'estado'         => 'Pendiente',
                    'created_at'     => date('Y-m-d H:i:s')
                ];

                $db->table('sanciones')->insert($data);
            }
        }

        return redirect()->to(base_url('admin/tarjetas'))->with('message', 'Sanciones guardadas correctamente.');
    }
    public function actualizarTarjeta()
    {
        $db = \Config\Database::connect();

        $id = $this->request->getPost('id');
        $fechas = $this->request->getPost('fechas_suspension');
        $estado = $this->request->getPost('estado');

        $data = [
            'fechas_suspension' => $fechas,
            'estado'            => $estado
        ];

        $db->table('sanciones')->where('id', $id)->update($data);

        return redirect()->to(base_url('admin/tarjetas'))->with('message', 'Sanción actualizada correctamente.');
    }
    // Listado de Goles (Pichichi Admin)
    public function listadoGoles()
    {
        $golModel   = new \App\Models\GolModel();
        $fechaModel = new \App\Models\FechaModel();

        $data = [
            'goleadores' => $golModel->getListadoGoles(),
            'fechas'     => $fechaModel->orderBy('id', 'DESC')->findAll(),
        ];
        // HTMX: Retornamos la vista que el hx-target va a renderizar
        return $this->render('admin/list_goles', $data);
    }

    // Listado de Tarjetas (Tribunal Admin)
    public function listadoTarjetas()
    {
        $db = \Config\Database::connect();

        // 1. Obtener las tarjetas para la tabla
        $builder = $db->table('sanciones s');
        $builder->select('s.*, j.nombres, j.apellidos, i.nombre as club');
        $builder->join('jugadores j', 's.jugador_id = j.id');
        $builder->join('instituciones i', 'j.institucion_id = i.id'); // El club viene del jugador
        $builder->orderBy('s.id', 'DESC');

        // IMPORTANTE: El nombre de la variable debe ser 'tarjetas' para coincidir con la vista
        $data['tarjetas'] = $builder->get()->getResultArray();

        // 2. Necesitamos las fechas para que el modal pueda cargar la cascada (Select 1)
        $data['fechas'] = $db->table('fechas')
            ->orderBy('id', 'DESC')
            ->get()
            ->getResultArray();

        return view('admin/tarjetas_view', $data);
    }
    public function getSeriesPorFecha($fechaId)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('partidos p');
        $builder->select('s.id, s.nombre');
        $builder->join('series s', 'p.serie_id = s.id');
        $builder->where('p.fecha_id', $fechaId);
        $builder->groupBy('s.id'); // Para que no se repitan las series

        $series = $builder->get()->getResultArray();
        return $this->response->setJSON($series); // Se lo envía al JavaScript del modal
    }
    public function getPartidosByFechaSerie($fechaId, $serieId)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('partidos p');

        // IMPORTANTE: Ponemos "as local_nombre" y "as visitante_nombre" 
        // para que coincida con tu JavaScript
        $builder->select('p.id, loc.nombre as local_nombre, vis.nombre as visitante_nombre');

        $builder->join('instituciones loc', 'p.institucion_local_id = loc.id');
        $builder->join('instituciones vis', 'p.institucion_visitante_id = vis.id');

        $builder->where('p.fecha_id', $fechaId);
        $builder->where('p.serie_id', $serieId);

        $partidos = $builder->get()->getResultArray();
        return $this->response->setJSON($partidos);
    }
    public function getJugadoresPartido($partidoId)
    {
        $db = \Config\Database::connect();

        // 1. Primero obtenemos los IDs de los dos equipos de ese partido
        $partido = $db->table('partidos')
            ->select('institucion_local_id, institucion_visitante_id')
            ->where('id', $partidoId)
            ->get()
            ->getRowArray();

        if (!$partido) {
            return $this->response->setJSON(['local' => [], 'visita' => []]);
        }

        // 2. Traemos jugadores del equipo LOCAL
        $local = $db->table('jugadores')
            ->select('id, nombres, apellidos')
            ->where('institucion_id', $partido['institucion_local_id'])
            ->where('estado', 'activo') // Opcional: solo los que pueden jugar
            ->get()
            ->getResultArray();

        // 3. Traemos jugadores del equipo VISITANTE
        $visita = $db->table('jugadores')
            ->select('id, nombres, apellidos')
            ->where('institucion_id', $partido['institucion_visitante_id'])
            ->where('estado', 'activo')
            ->get()
            ->getResultArray();

        // Enviamos ambos grupos al JavaScript
        return $this->response->setJSON([
            'local' => $local,
            'visita' => $visita
        ]);
    }
    public function borrarGol($id)
    {
        $db = \Config\Database::connect();
        $db->table('goles')->delete(['id' => $id]);

        // Usamos 'success' porque es el que busca tu Layout para el Toast
        return redirect()->back()->with('success', 'El gol ha sido eliminado correctamente.');
    }
}
