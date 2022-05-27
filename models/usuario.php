<?php
//CONFIG DATABASE
require_once('../config/database.php');

function getAllUsers() //1
{
}

function getUserByEmail($email) //2
{
}

function addUser($usuario)
{
    $usuario = (object) $usuario;

    $conn = connect();

    $query = "INSERT INTO usuarios (nombre, apellido, email, password, id_rol) 
    VALUES ('$usuario->nombre', '$usuario->apellido', '$usuario->email', '$usuario->password', '$usuario->id_rol')";

    try {
        mysqli_query($conn, $query);
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }

    disconnect($conn);
    return true;
}

function updateUser($usuario)
{
    $usuario = (object) $usuario;
    $conn = connect();

    $query = "UPDATE usuarios SET nombre = '$usuario->nombre', apellido = '$usuario->apellido', email='$usuario->email', id_rol='$usuario->id_rol' WHERE id_usuario='$usuario->id_usuario'";

    try {
        mysqli_query($conn, $query);
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
        return false;
    }

    disconnect($conn);
    return true;
}

function deleteUser($id) //5
{
}

function filterUsers($filter) //6
{
    $conn = connect();
    $row = array();
    $string = "SELECT id_usuario, nombre, apellido, email, id_rol FROM 
    usuarios WHERE (nombre LIKE '%$filter%' 
    OR apellido LIKE '%$filter%' OR email LIKE '%$filter%') ORDER BY nombre ASC";

    $query = mysqli_query($conn, $string);
    $nRow = mysqli_num_rows($query);

    if ($nRow != 0) {
        while ($Usuarios = mysqli_fetch_array($query)) {
            $jsonRow = array();
            $id_usuario = $Usuarios["id_usuario"];
            $nombre = $Usuarios["nombre"];
            $apellido = $Usuarios["apellido"];
            $email = $Usuarios["email"];
            $id_rol = $Usuarios["id_rol"];
            $jsonRow["id_usuario"] = $id_usuario;
            $jsonRow["nombre"] = $nombre;
            $jsonRow["apellido"] = $apellido;
            $jsonRow["email"] = $email;
            $jsonRow["id_rol"] = $id_rol;
            $row[] = $jsonRow;
        }
    }
    disconnect($conn);
    return array_values($row);
}

function isEmailExist($email)
{
    $conn = connect();

    $query = "SELECT * FROM usuarios WHERE email = '$email'";

    try {
        $row = mysqli_query($conn, $query);
        if (mysqli_num_rows($row) > 0) {
            disconnect($conn);
            return true;
        }
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }

    disconnect($conn);
    return false;
}

function getUserById($id)
{
    $conn = connect();

    $query = "SELECT nombre, apellido, email, id_rol FROM usuarios WHERE id_usuario='$id'";

    try {
        $row = mysqli_query($conn, $query);
        $result = mysqli_fetch_assoc($row);
    } catch (Exception $e) {
        $result = "Error: " . $e->getMessage();
    }

    disconnect($conn);
    return (object) $result;
}
