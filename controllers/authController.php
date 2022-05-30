<?php

require_once('../utils/validations/authValidation.php');
require_once('../models/usuario.php');
require_once('../utils/auth.php');

//INICIALIZAR VARIABLES
$data = array();
$action = '';

//EJECUTAR CONSULTAS
if (isset($_POST['action'])) {
    $action = strtolower($_POST['action']);
}

if ($action === 'login') {

    $crendenciales = [
        'email' => isset($_POST['email']) ? trim($_POST['email']) : '',
        'password' => isset($_POST['password']) ? trim($_POST['password']) : ''
    ];

    $errors = loginValidation($crendenciales);

    if (count($errors)) {
        header('HTTP/1.O 400 Bad Request');
        $data = [
            'state' => 'error',
            'title' => 'Errores de validación',
            'validationError' => $errors
        ];
    } else {
        try {
            $usuario = getUserByEmail($crendenciales['email'])[0];
            $token = generateToken($usuario);

            $data = [
                'state' => 'success',
                'title' => 'Bienvenido, ' . $usuario['nombre'] . ' ' . $usuario['apellido'] . '!',
                'token' => $token
            ];
        } catch (Exception $e) {
            header('HTTP/1.O 500 Internal Error');
            $data = [
                'state' => 'error',
                "title" => "SQL EXCEPCTION: Error en la consulta",
                "msg" => $e->getMessage()
            ];
        }
    }

    print json_encode($data);
}




if ($action === 'logout') {
}
