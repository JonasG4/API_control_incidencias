<?php
//LIBRERIAS DE JWT
require_once('../vendor/autoload.php');
require_once('../utils/auth.php');
require_once('../utils/validations.php');

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

//INICIALIZAR VARIABLES
$data = [];

$action = '';

//MODELOS
require_once('../models/usuario.php');

//EJECUTAR CONSULTAS
if (isset($_POST['action'])) {
    $action = strtolower($_POST['action']);
}

if($action == 'seleccionar') {
    $data["status"] = 200;
    $data["result"] = getAllUsers();
    echo json_encode($data);
}

if($action == 'listar_email') {
    $email = $_POST["email"];
    $data["result"] = getUserByEmail($email);
    echo json_encode($data);
}

if ($action === 'registrar' && isAdmin()) {

    $usuario = [
        'nombre' => isset($_POST['nombre']) ? trim($_POST['nombre']) : '',
        'apellido' => isset($_POST['apellido']) ? trim($_POST['apellido']) : '',
        'email' => isset($_POST['email']) ? trim($_POST['email']) : '',
        'password' => isset($_POST['password']) ? trim($_POST['password']) : '',
        'confirmPassword' => isset($_POST['confirmPassword']) ? trim($_POST['confirmPassword']) : ''
    ];

    $errors = usuarioValidation($usuario);
    if (count($errors)) {
        $data = [
            'title' => 'Errores de validación',
            'validationError' => $errors
        ];
    } else {
        $usuario['id_rol'] = 1;
        $usuario['password'] = password_hash($usuario['password'], PASSWORD_BCRYPT);
        try {
            addUser($usuario);
            $data = [
                "title" => 'Usuario agregado exitosamente',
                'usuario' => $usuario
            ];
        } catch (Exception $e) {
            $data = [
                "title" => "SQL EXCEPCTION: Error en la consulta",
                "msg" => $e->getMessage()
            ];
        }
    }
    echo json_encode($data);
}

if ($action === 'actualizar' && isAuth()) {

    if (isset($_POST['id_usuario'])) {
        $usuario = [
            'id_usuario' => trim($_POST['id_usuario']),
            'nombre' => isset($_POST['nombre']) ? trim($_POST['nombre']) : '',
            'apellido' => isset($_POST['apellido']) ? trim($_POST['apellido']) : '',
            'email' => isset($_POST['email']) ? trim($_POST['email']) : '',
            'id_rol' => isset($_POST['id_rol']) ? trim($_POST['id_rol']) : 1,
        ];

        $errors = usuarioUpdateValidation($usuario);

        if (count($errors)) {
            $data = [
                'title' => 'Errores de validación',
                'validationError' => $errors
            ];
        } else {
            try {
                updateUser($usuario);
                $data = [
                    "title" => 'Usuario actualizado exitosamente',
                    'usuario' => $usuario
                ];
            } catch (Exception $e) {
                $data = [
                    "title" => "SQL EXCEPCTION: Error en la consulta",
                    "msg" => $e->getMessage()
                ];
            }
        }
    } else {
        $data =
            [
                'title' => 'Id de usuario no proporcionado',
                'msg' => 'Es necesario el id de usuario para actualizar el registro.'
            ];
    }

    echo json_encode($data);
}
