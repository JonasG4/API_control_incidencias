<?php
require_once("../../models/incidente.php");

function deleteValidation($id_incidente)
{
    $errors = [];

    $isIncidentExists = (array) getIncidentById($id_incidente);

    if (count( $isIncidentExists) < 1) {
        $errors = [
            "id_incidente" => "No existe ningun incidente con el id: {$id_incidente}"
        ];
    }

    return $errors;
}