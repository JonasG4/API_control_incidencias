<?php
function updateValidationAdmin($incident)   {
  $errors = [];

  $estado = trim($incident['estado']);
  $nota = trim($incident['nota']);

  if(strlen($nota) < 7) {
    $errors['nota'] = "La nota debe tener mas de 7 carácteres";
  }

  if($estado < 0 && $estado > 2) {
    $errors['estado'] = "Opción para estado inválida";
  }

  return $errors;
}  
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