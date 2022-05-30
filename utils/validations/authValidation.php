<?php

require('../models/usuario.php');

function loginValidation($credenciales)
{
    $errors = [];
    $email = trim($credenciales['email']);
    $password = trim($credenciales['password']);


    if (!isEmailExist($email)) {
        $errors['email'] = 'El correo electrónico no está registrado';
    } else {
        $usuario = getUserByEmail($email);
        if (!password_verify($password, $usuario[0]['password'])) {
            $errors['password'] = 'El correo electrónico o la contraseña son incorrectos';
        }
    }

    return $errors;
}

function signupValidation($usuario)
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