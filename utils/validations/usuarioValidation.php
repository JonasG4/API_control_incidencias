<?php

require('../models/usuario.php');

function createValidation($usuario)
{
    $errors = [];

    $nombre = trim($usuario['nombre']);
    $apellido = trim($usuario['apellido']);
    $email = trim($usuario['email']);
    $password = trim($usuario['password']);
    $confirmPassword = trim($usuario['confirmPassword']);

    //regex
    $uppercase = preg_match('@[A-Z]@', $password);
    $lowercase = preg_match('@[a-z]@', $password);
    $number    = preg_match('@[0-9]@', $password);


    //Validar campo nombre
    if (strlen($nombre) < 3) {
        $errors['nombre'] = "El nombre debe tener mas de 2 carácteres";
    }

    //Validar campo apellido
    if (strlen($apellido) < 3) {
        $errors['apellido'] = "El apellido debe tener mas de 2 carácteres";
    }

    //Validar campo email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Ingrese un formato de correo válido. Ejp: nombre@app.com";
    } else if (isEmailExist($email)) {
        $errors['email'] = "El email ya existe.";
    }

    //validar campo password
    if (!$uppercase || !$lowercase || !$number || strlen($password) < 6) {
        $errors['password'] = "La contraseña debe tener al menos 6 carácteres, 1 letra mayúscula, 1 letra minúscula y un número.";
    }

    //validar campo confirmPassword
    if ($password !== $confirmPassword) {
        $errors['confirmPassword'] = "Las contraseñas no coinciden.";
    }

    return $errors;
}

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

function resetPasswordValidation($credenciales)
{
    $errors = [];

    $newPassword = $credenciales['newPassword'];
    $confirmPassword = $credenciales['confirmPassword'];
    //regex
    $isUppercase = preg_match('@[A-Z]@', $newPassword);
    $isLowercase = preg_match('@[a-z]@', $newPassword);
    $isNumber    = preg_match('@[0-9]@', $newPassword);


    $usuario = (array) getUserById($credenciales['id_usuario']);
    $isUserExists = count($usuario) < 1;

    if ($isUserExists) {
        $errors = [
            "id_usuario" => "No existe ningun usuario con el id: {$credenciales['id_usuario']}"
        ];
    }
    
    //validar campo password
    if (!$isUppercase || !$isLowercase || !$isNumber || strlen($newPassword) < 6) {
        $errors['newPassword'] = "La contraseña debe tener al menos 6 carácteres, 1 letra mayúscula, 1 letra minúscula y un número.";
    }

    if ($newPassword !== $confirmPassword) {
        $errors["confirmPassword"] = "Las contraseñas no coinciden.";
    }




    return $errors;
}
function changePasswordValidation($credenciales)
{
    $errors = [];

    $oldPassword = $credenciales['oldPassword'];
    $newPassword = $credenciales['newPassword'];
    $confirmPassword = $credenciales['confirmPassword'];
    //regex
    $isUppercase = preg_match('@[A-Z]@', $newPassword);
    $isLowercase = preg_match('@[a-z]@', $newPassword);
    $isNumber    = preg_match('@[0-9]@', $newPassword);


    $usuario = (array) getUserById($credenciales['id_usuario']);
    $usuarioPassword = getUserByEmail($usuario['email'])['password'];
    $isUserExists = count($usuario) < 1;

    if ($isUserExists) {
        $errors = [
            "id_usuario" => "No existe ningun usuario con el id: {$credenciales['id_usuario']}"
        ];
    }

    if (!password_hash($oldPassword, $usuarioPassword)) {
        $errors = [
            "oldPassword" => "La contraseña es incorrecta. Vuelva a intentarlo."
        ];
    }

    //validar campo password
    if (!$isUppercase || !$isLowercase || !$isNumber || strlen($newPassword) < 6) {
        $errors['newPassword'] = "La contraseña debe tener al menos 6 carácteres, 1 letra mayúscula, 1 letra minúscula y un número.";
    }

    if ($newPassword !== $confirmPassword) {
        $errors["confirmPassword"] = "Las contraseñas no coinciden.";
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
