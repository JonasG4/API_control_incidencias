<?php
//Utils
require_once('../utils/auth.php');
require_once('../utils/validations/usuarioValidation.php');

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
if ($action == 'list') {
    $data["status"] = 200;
    $data["result"] = getAllUsers();
    echo json_encode($data);
}

//Obtener usuario por email
if ($action == 'listar_email') {
    $email = $_POST["email"];
    $data["result"] = getUserByEmail($email);
    echo json_encode($data);
}

// REGISTAR UN USUARIO
if ($action === 'create' && isAdmin()) {

    $usuario = [
        'nombre' => isset($_POST['nombre']) ? trim($_POST['nombre']) : '',
        'apellido' => isset($_POST['apellido']) ? trim($_POST['apellido']) : '',
        'email' => isset($_POST['email']) ? trim($_POST['email']) : '',
        'password' => isset($_POST['password']) ? trim($_POST['password']) : '',
        'confirmPassword' => isset($_POST['confirmPassword']) ? trim($_POST['confirmPassword']) : ''
    ];

    $errors = createValidation($usuario);
    if (count($errors)) {
        header('HTTP/1.O 400 Bad Request');
        $data = [
            'state' => 'error',
            'title' => 'Errores de validación',
            'validationError' => $errors
        ];
    } else {
        $usuario['id_rol'] = 1;
        $usuario['password'] = password_hash($usuario['password'], PASSWORD_BCRYPT);
        try {

            //Añadir usuario
            addUser($usuario);

            //Remover password del array
            unset($usuario['password']);
            unset($usuario['confirmPassword']);

            //Retorno de datos
            $data = [
                'state' => 'success',
                "title" => 'Usuario agregado exitosamente',
                'usuario' => $usuario
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
    echo json_encode($data);
}

// MODIFICAR UN USUARIO
if ($action === 'update' && isAuth()) {
    if (isset($_POST['id_usuario'])) {
        $usuario = [
            'id_usuario' => trim($_POST['id_usuario']),
            'nombre' => isset($_POST['nombre']) ? trim($_POST['nombre']) : '',
            'apellido' => isset($_POST['apellido']) ? trim($_POST['apellido']) : '',
            'email' => isset($_POST['email']) ? trim($_POST['email']) : '',
            'id_rol' => isset($_POST['id_rol']) ? trim($_POST['id_rol']) : 1,
        ];

        $errors = updateValidation($usuario);

        if (count($errors)) {
            header('HTTP/1.O 400 Bad Request');
            $data = [
                'state' => 'error',
                'title' => 'Errores de validación',
                'validationError' => $errors
            ];
        } else {
            try {
                updateUser($usuario);
                $data = [
                    'state' => 'success',
                    "title" => 'Usuario actualizado exitosamente',
                    'usuario' => $usuario
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
    } else {
        header('HTTP/1.O 400 Bad Request');
        $data = [
            'state' => 'error',
            'title' => 'Id de usuario no fue proporcionado',
            'msg' => 'Es necesario el id de usuario para actualizar el registro especifico.'
        ];
    }


    echo json_encode($data);
}

// ELIMINAR UN USUARIO
if ($action === 'delete' && isAuth()) {
    if (isset($_POST['id_usuario'])) {

        $id_usuario = trim($_POST['id_usuario']);
        $errors = deleteValidation($id_usuario);

        if (count($errors) > 0) {
            header('HTTP/1.O 400 Bad Request');
            $data = [
                'state' => 'error',
                'title' => 'Errores de validación',
                'validationError' => $errors
            ];
        } else {

            try {
                deleteUser($id_usuario);
                $data = [
                    'state' => 'success',
                    "title" => "Registro elimnado exitosamente.",
                    "msg" => "Se ha eliminado el usuario con id: {$id_usuario}"
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
    } else {
        header('HTTP/1.O 400 Bad Request');
        $data = [
            'state' => 'error',

            'title' => 'Id de usuario no fue proporcionado',
            'msg' => 'Es necesario el id de usuario para eliminar el registro especifico.'
        ];
    }


    echo json_encode($data);
}

if ($action === 'send_email_reset_password') {
    if (isset($_POST['email'])) {
        $credentials = [
            'email' => isset($_POST['email']) ? $_POST['email'] : "",
        ];

        if (!isEmailExist($credentials['email'])) {
            header('HTTP/1.O 400 Bad Request');
            $data = [
                'state' => 'error',
                'title' => 'Errores de validación',
                'validationError' => 'El email proporcionado no está registrado.'
            ];
        } else {
            try {
                $codigo = rand(1000, 9999);
                $to = $credentials['email'];
                $subjet = "Restaurar Contraseña [NO REPLY]";
                $headers = "FROM: support@sysgi.com <support@sysgi.com>";
                $messsage = "Restaura tu contraseña con el siguiente codigo de verificacion: {$codigo}";
                @mail($to, $subjet, $messsage, $headers);
                $data = [
                    "state" => "sucesss",
                    "result" => "Correo enviado exitosamente!",
                    "codigo" => $codigo
                ];
            } catch (Exception $e) {
                header('HTTP/1.O 500 Internal Error');
                $data = [
                    'state' => 'error',
                    "title" => "PHP MAIL FUNCTION ERROR",
                    "msg" => $e->getMessage()
                ];
            }
        }
    } else {
        header('HTTP/1.O 400 Bad Request');
        $data = [
            'state' => 'error',
            'title' => 'Id de usuario no fue proporcionado',
            'msg' => 'Es necesario el id de usuario para actualizar el registro especifico.'
        ];
    }
    echo json_encode($data);
}

if ($action === 'reset_password') {
    if (isset($_POST['id_usuario'])) {
        $credentials = [
            'id_usuario' => isset($_POST['id_usuario']) ? $_POST['id_usuario'] : 0,
            'newPassword' => isset($_POST['newPassword']) ? trim($_POST['newPassword']) : "",
            'confirmPassword' => isset($_POST['newPassword']) ? trim($_POST['newPassword']) : ""
        ];

        $errors = resetPasswordValidation($credentials);

        if (count($errors)) {
            header('HTTP/1.O 400 Bad Request');
            $data = [
                'state' => 'error',
                'title' => 'Errores de validación',
                'validationError' => $errors
            ];
        } else {
            try {
                updatePassword($credentials);
                $data = [
                    'state' => 'success',
                    "title" => 'Contraseña actualizada exitosamente'
                ];
            } catch (Exception $e) {
                header('HTTP/1.O 500 Internal Error');
                $data = [
                    'state' => 'error',
                    "title" => "SQL EXCEPTION: Error en la consulta",
                    "msg" => $e->getMessage()
                ];
            }
        }
    } else {
        header('HTTP/1.O 400 Bad Request');
        $data = [
            'state' => 'error',
            'title' => 'Id de usuario no fue proporcionado',
            'msg' => 'Es necesario el id de usuario para actualizar el registro especifico.'
        ];
    }
    echo json_encode($data);
}

if ($action === 'change_password') {
    if (isset($_POST['id_usuario'])) {
        $credentials = [
            'id_usuario' => isset($_POST['id_usuario']) ? $_POST['id_usuario'] : 0,
            'oldPassword' => isset($_POST['oldPassword']) ? trim($_POST['oldPassword']) : "",
            'newPassword' => isset($_POST['newPassword']) ? trim($_POST['newPassword']) : "",
            'confirmPassword' => isset($_POST['newPassword']) ? trim($_POST['newPassword']) : ""
        ];

        $errors = changePasswordValidation($credentials);

        if (count($errors)) {
            header('HTTP/1.O 400 Bad Request');
            $data = [
                'state' => 'error',
                'title' => 'Errores de validación',
                'validationError' => $errors
            ];
        } else {
            try {
                updatePassword($credentials);
                $data = [
                    'state' => 'success',
                    "title" => 'Contraseña actualizada exitosamente'
                ];
            } catch (Exception $e) {
                header('HTTP/1.O 500 Internal Error');
                $data = [
                    'state' => 'error',
                    "title" => "SQL EXCEPTION: Error en la consulta",
                    "msg" => $e->getMessage()
                ];
            }
        }
    } else {
        header('HTTP/1.O 400 Bad Request');
        $data = [
            'state' => 'error',
            'title' => 'Id de usuario no fue proporcionado',
            'msg' => 'Es necesario el id de usuario para actualizar el registro especifico.'
        ];
    }
    echo json_encode($data);
}

if ($action === 'search' && isAuth()) {
    $filter =  trim($_POST['filter']);

    try {
        $usuarios = filterUsers($filter);
        $data = [
            'state' => 'success',
            'title' => 'Usuarios filtrados con éxito. Cantidad: ' . count($usuarios) . ' usuario(s)',
            'usuarios' => $usuarios
        ];
    } catch (Exception $e) {
        header('HTTP/1.O 500 Internal Error');
        $data = [
            'state' => 'error',
            "title" => "SQL EXCEPCTION: Error en la consulta",
            "msg" => $e->getMessage()
        ];
    }

    echo json_encode($data);
}
