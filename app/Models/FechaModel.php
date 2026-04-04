<?php

namespace App\Models;

use CodeIgniter\Model;

class FechaModel extends Model
{
    protected $table      = 'fechas';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'temporada_id',      // ¡IMPORTANTE! Agregar esto para poder crear la Temp 2
        'nombre_fecha',
        'fecha_calendario',
        'estado',
        'estado_infantiles',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';
    protected $useSoftDeletes = true;

    /**
     * Obtiene las fechas de la temporada que esté marcada como ACTUAL
     */
    public function getFechasConTemporada($temporada_id)
    {
        return $this->select('fechas.*, temporadas.nombre_temporada, temporadas.actual as temporada_activa')
            ->join('temporadas', 'temporadas.id = fechas.temporada_id')
            ->where('temporadas.actual', '1') // Solo trae lo de la temporada vigente
            ->orderBy('fechas.id', 'ASC')
            ->findAll();
    }

    /**
     * Nota: Este método parece pertenecer más al PartidoModel 
     * ya que consulta la tabla 'principal'. Tenlo en cuenta si te da error.
     */
    public function getDueloConNombres($id)
    {
        return $this->db->table('principal') // Especificamos la tabla si no es 'fechas'
            ->select('principal.*, l.nombre as local_nombre, v.nombre as visitante_nombre')
            ->join('instituciones l', 'l.id = principal.institucion_local_id')
            ->join('instituciones v', 'v.id = principal.institucion_visitante_id')
            ->where('principal.id', $id)
            ->get()->getRowArray();
    }

    public function getConteosGenerales($temporada_id)
    {
        return [
            'totalfechas'      => $this->where('temporada_id', $temporada_id)->countAllResults(),
            'fechasjugadas'    => $this->where(['temporada_id' => $temporada_id, 'estado' => 'jugada'])->countAllResults(),
            'fechaspendientes' => $this->where(['temporada_id' => $temporada_id, 'estado' => 'pendiente'])->countAllResults(),
        ];
    }

    /**
     * Busca la fecha activa filtrando SIEMPRE por la temporada_id actual
     */
    public function getFechaActiva($temporada_id, $campoEstado = 'estado')
    {
        // 1. Buscar suspendida en la temporada actual
        $fecha = $this->where([$campoEstado => 'suspendida', 'temporada_id' => $temporada_id])->first();

        // 2. Si no, buscar la próxima pendiente de esta temporada
        if (!$fecha) {
            $fecha = $this->where([$campoEstado => 'pendiente', 'temporada_id' => $temporada_id])
                ->orderBy('id', 'ASC')->first();
        }

        // 3. Si no, la última jugada de esta temporada
        if (!$fecha) {
            $fecha = $this->where([$campoEstado => 'jugada', 'temporada_id' => $temporada_id])
                ->orderBy('id', 'DESC')->first();
        }

        return $fecha;
    }
}
