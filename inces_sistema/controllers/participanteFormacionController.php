<?php
require_once __DIR__ . '/../models/ParticipanteFormacion.php';

class ParticipanteFormacionController
{
    public function asignar($id_participante, $id_formacion)
    {
        $modelo = new ParticipanteFormacion();
        return $modelo->asignar($id_participante, $id_formacion);
    }

    public function eliminarAsignacion($id_asignacion)
    {
        $modelo = new ParticipanteFormacion();
        return $modelo->eliminarAsignacion($id_asignacion);
    }
}
