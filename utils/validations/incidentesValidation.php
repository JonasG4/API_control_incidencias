<?php
require_once("../models/incidente.php");
require_once("../models/usuario.php");

function createValidation($incident)
{
  $errors = [];

  $tipo = trim(($incident['tipo']));
  $descripcion = trim($incident['descripcion']);
  $id_usuario = trim($incident['id_usuario']);
  $estado = trim($incident['estado']);
  $imagen = trim($incident['imagen']);

  $isUserExists = (array) getUserById($id_usuario);

  if (strlen($tipo) < 1) {
    $errors['tipo'] = "No se ha seleccionado ningun tipo";
  }


  if (strlen($descripcion) < 3) {
    $errors['descripcion'] = "La descripcion debe tener al menos 3 caracteres";
  }

  if (count($isUserExists) < 1) {
    $errors['id_usuario'] = "No existe el usuario con id: {$id_usuario}";
  }

  if (empty($imagen)) {
    $errors['iamgen'] = "No se ha subido ninguna imagen";
  }
  if ($estado < 0 && $estado > 2) {
    $errors['estado'] = "Opción para estado inválida";
  }

  return $errors;
}

function updateValidation($incident)
{
  $errors = [];

  $id_incidente = trim($incident['id_incidente']);
  $tipo = trim($incident['tipo']);
  $descripcion = trim($incident['descripcion']);

  $isIncidentExists = (array) getIncidentById($id_incidente);

  if (count($isIncidentExists) < 1) {
    $errors['id_incidente'] = "No existe ningun incidente con el id proporcionado";
  }

  if(empty($tipo)){
    $errros['tipo'] = "El campo tipo no puede quedar vacio";
  }

  if(strlen($descripcion) < 5){
    $errors['tipo'] = "La descripcion debe tener al menos 5 caracteres";
  }

  return $errors;
}

function updateImagenValidation($incident){
  $errors = [];

  $id_incidente = trim($incident['id_incidente']);
  $imagen = trim($incident['imagen']);

  $isIncidentExists = (array) getIncidentById($id_incidente);

  if (count($isIncidentExists) < 1) {
    $errors['id_incidente'] = "No existe ningun incidente con el id proporcionado";
  }

  if (empty($imagen)) {
    $errors['iamgen'] = "No se ha subido ninguna imagen";
  }

  return $errors;
}

function updateValidationAdmin($incident)
{
  $errors = [];

  $estado = trim($incident['estado']);
  $nota = trim($incident['nota']);

  if (strlen($nota) < 7) {
    $errors['nota'] = "La nota debe tener mas de 7 carácteres";
  }

  if ($estado < 0 && $estado > 2) {
    $errors['estado'] = "Opción para estado inválida";
  }

  return $errors;
}

function deleteValidation($id_incidente)
{
  $errors = [];

  $isIncidentExists = (array) getIncidentById($id_incidente);

  if (count($isIncidentExists) < 1) {
    $errors = [
      "id_incidente" => "No existe ningun incidente con el id: {$id_incidente}"
    ];
  }

  return $errors;
}
