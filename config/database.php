<?php

function connect()
{

    // LOCALHOST

    // $database = [
    //     "server" => 'localhost',
    //     "username" => 'root',
    //     "password" => '',
    //     "db_name" => 'dbsys_incidencias'
    // ];

    //  HEROKU REMOTE 
    $database = [
        "server" => 'us-cdbr-east-05.cleardb.net',
        "username" => 'b1645921b59209',
        "password" => '6ce747a7',
        "db_name" => 'heroku_1facf0ac051c124'
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
