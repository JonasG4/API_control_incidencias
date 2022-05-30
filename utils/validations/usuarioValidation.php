<?php

require('../models/usuario.php');


function updateValidation($usuario)
{
    $errors = [];

    $id_usuario = trim($usuario['id_usuario']);
    $nombre = trim($usuario['nombre']);
    $apellido = trim($usuario['apellido']);
    $newEmail = trim($usuario['email']);

    $currentEmail = getUserById($id_usuario)['email'];
    if (isset($currentEmail)) {
        //Validar campo nombre
        if (strlen($nombre) < 3) {
            $errors['nombre'] = "El nombre debe tener mas de 2 carácteres";
        }

        //Validar campo apellido
        if (strlen($apellido) < 3) {
            $errors['apellido'] = "El apellido debe tener mas de 2 carácteres";
        }

        //Validar campo email
        if (!filter_var($newEmail, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = "Ingrese un formato de correo válido. Ejp: nombre@app.com";
        } else if ($currentEmail !== $newEmail && isEmailExist($newEmail)) {
            $errors['email'] = "El email ya existe.";
        }
    } else {
        $errors = [
            "id_usuario" => "No existe ningun usuario con el id: {$id_usuario}"
        ];
    }

    return $errors;
}

function deleteValidation($id_usuario)
{
    $errors = [];

    $isUserExists = (array) getUserById($id_usuario);

    if (count($isUserExists) < 1) {
        $errors = [
            "id_usuario" => "No existe ningun usuario con el id: {$id_usuario}"
        ];
    }

    return $errors;
}

