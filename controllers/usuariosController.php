<?php
//Utils
require_once('../utils/auth.php');
require_once('../utils/validations.php');

//MODELOS
require_once('../models/usuario.php');

//INICIALIZAR VARIABLES
$data = [];

$action = '';

//EJECUTAR CONSULTAS
if (isset($_POST['action'])) {
    $action = strtolower($_POST['action']);
}
// ========== ACCIONES ============================= 

//Obtener todos los usuarios
if($action == 'seleccionar') {
    $data["status"] = 200;
    $data["result"] = getAllUsers();
    echo json_encode($data);
}

//Obtener usuario por email
if($action == 'listar_email') {
    $email = $_POST["email"];
    $data["result"] = getUserByEmail($email);
    echo json_encode($data);
}

// REGISTAR UN USUARIO
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
        header('HTTP/1.O 400 Bad Request');
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
            header('HTTP/1.O 500 Internal Error');
            $data = [
                "title" => "SQL EXCEPCTION: Error en la consulta",
                "msg" => $e->getMessage()
            ];
        }
    }
    echo json_encode($data);
}

// MODIFICAR UN USUARIO
if ($action === 'modificar' && isAuth()) {

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
            header('HTTP/1.O 400 Bad Request');
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
                header('HTTP/1.O 500 Internal Error');
                $data = [
                    "title" => "SQL EXCEPCTION: Error en la consulta",
                    "msg" => $e->getMessage()
                ];
            }
        }
    } else {
        header('HTTP/1.O 400 Bad Request');
        $data =
            [
                'title' => 'Id de usuario no fue proporcionado',
                'msg' => 'Es necesario el id de usuario para actualizar el registro especifico.'
            ];
    }


    echo json_encode($data);
}

// ELIMINAR UN USUARIO
if ($action === 'eliminar' && isAuth()) {
    if (isset($_POST['id_usuario'])) {

        $id_usuario = trim($_POST['id_usuario']);
        $errors = usuarioDeleteValidation($id_usuario);

        if (count($errors) > 0) {
            header('HTTP/1.O 400 Bad Request');
            $data = [
                'title' => 'Errores de validación',
                'validationError' => $errors
            ];
        } else {

            try {
                deleteUser($id_usuario);
                $data = [
                    "title" => "Registro elimnado exitosamente.",
                    "msg" => "Se ha eliminado el usuario con id: {$id_usuario}"
                ];
            } catch (Exception $e) {
                header('HTTP/1.O 500 Internal Error');
                $data = [
                    "title" => "SQL EXCEPCTION: Error en la consulta",
                    "msg" => $e->getMessage()
                ];
            }
        }
    } else {
        header('HTTP/1.O 400 Bad Request');
        $data =
            [
                'title' => 'Id de usuario no fue proporcionado',
                'msg' => 'Es necesario el id de usuario para eliminar el registro especifico.'
            ];
    }


    echo json_encode($data);
}

if ($action === 'buscar' && isAuth()) {
    $filter =  trim($_POST['filter']);

    try {
        $usuarios = filterUsers($filter);
        $data = [
            'title' => 'Usuarios filtrados con éxito. Cantidad: ' . count($usuarios) . ' usuario(s)',
            'usuarios' => $usuarios
        ];
    } catch (Exception $e) {
        header('HTTP/1.O 500 Internal Error');
        $data = [
            "title" => "SQL EXCEPCTION: Error en la consulta",
            "msg" => $e->getMessage()
        ];
    }

    echo json_encode($data);
}


if ($action === 'gettoken') {
    $usuario = [
        'nombre' => 'Jonas',
        'apellido' => 'Garcia',
        'id_rol' => 2
    ];

    echo json_encode(generateToken($usuario));
}
