<?php
//CONFIG DATABASE
require_once('../config/database.php');

function getAllUsers() //1
{

}

function getUserByEmail($email) //2
{
}

function addUser($user) 
{

}

function updateUser($user) 
{

}

function deleteUser($id) //5
{
    $conn = connect();

    $query = "UPDATE usuarios SET nombre='$name',
     apellido='$lastname',email='$email', id_rol='$id_rol'  WHERE id_usuario='$id_usuario'";
    if (mysqli_query($conn, $query)) {
        $state = 1;
    } else {
        $state = "Error: " . mysqli_error($conn);
    }
    disconnect($conn);
    return $state;
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