<?php

function JWTConfig()
{
    $time = time();
    $JWT = [
        'secret' => 'AS6XzF7FS099dkah20d28hJ9K39hah8has89dahsLA8sd',
        'iat' => $time, //Tiempo que inició el token
        'exp' => $time + (60 * 60 * 24), //Tiempo que expirará el token (1 dia)
        'algorithm' => 'HS256'
    ];

    return (object) $JWT;
}
