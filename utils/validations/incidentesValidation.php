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