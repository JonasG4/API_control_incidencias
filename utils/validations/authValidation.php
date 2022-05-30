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

