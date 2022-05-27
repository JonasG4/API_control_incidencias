<?php

function connect()
{

    $database = [
        "server" => 'localhost',
        "username" => 'root',
        "password" => '',
        "db_name" => 'dbsys_incidencias00'
    ];

    $conn = mysqli_connect($database['server'], $database['username'], $database['password'], $database['db_name']);


    if (!$conn) {
        $conn = mysqli_errno($conn);
    }

    return $conn;
}

function disconnect($conn)
{
    try {
        mysqli_close($conn);
    } catch (Exception $e) {
        print($e->getMessage());
    }
}
