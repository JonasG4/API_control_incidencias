<?php

function connect()
{
    //Credentials
    $server = "localhost";
    $username = "root";
    $password = "";
    $db_name = "miagenda";

    $conn = mysqli_connect($server,
     $username, $password, $db_name);

    if (!$conn) {
        $conn = mysqli_error($conn);
    }
    return $conn;
}

function disconnect($conn)
{
    try {
        mysqli_close($conn);
        $status = 1;
    } catch (Exception $e) {
        $status = 0;
    }

    return $status;
}

function addContact($name, $phone)
{
    $conn = connect();

    $query = "INSERT INTO contactos (nombre, telefono)
     VALUES ('$name','$phone')";
     
    if (mysqli_query($conn, $query)) {
        $state = 1;
    } else {
        $state = "Error: " . mysqli_error($conn);
    }
    disconnect($conn);
    return $state;
}

function getAllContactsBy($filter)
{
    $conn = connect();
    $row = array();
    $string = "SELECT id_contacto, nombre, telefono FROM 
    contactos WHERE (nombre LIKE '%$filter%' 
    OR telefono LIKE '%$filter%') ORDER BY nombre ASC";
    
    $query = mysqli_query($conn, $string);
    $nRow = mysqli_num_rows($query);

    if ($nRow != 0) {
        while ($Contactos = mysqli_fetch_array($query)) {
            $jsonRow = array();
            $id_contact = $Contactos["id_contacto"];
            $nombre = $Contactos["nombre"];
            $telefono = $Contactos["telefono"];
            $jsonRow["id_contacto"] = $id_contact;
            $jsonRow["nombre"] = $nombre;
            $jsonRow["telefono"] = $telefono;
            $row[] = $jsonRow;
        }
    }

    disconnect($conn);
    return array_values($row);
}

function updateContact($id_contact, $name, $phone)
{
    $conn = connect();

    $query = "UPDATE contactos SET nombre='$name',
     telefono='$phone' WHERE id_contacto='$id_contact'";
    if (mysqli_query($conn, $query)) {
        $state = 1;
    } else {
        $state = "Error: " . mysqli_error($conn);
    }
    disconnect($conn);
    return $state;
}

function deleteContact($id_contact)
{
    $conn = connect();

    $query = "DELETE FROM contactos 
    WHERE id_contacto='$id_contact'";
    if (mysqli_query($conn, $query)) {
        $state = 1;
    } else {
        $state = "Error: " . mysqli_error($conn);
    }
    disconnect($conn);
    return $state;
}

?>