<?php

require_once('../vendor/autoload.php');
require_once('../config/jwt.php');

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\SignatureInvalidException;

$data = [];

function isAdmin()
{
   if (!empty($_SERVER["HTTP_AUTHORIZATION"])) {
      $token = $_SERVER['HTTP_AUTHORIZATION'];
      $key = JWTConfig()->secret;
      $algorithm = JWTConfig()->algorithm;
      try {
         $data = JWT::decode($token, new Key($key, $algorithm));
         if ($data->usuario->id_rol == 2) {
            return true;
         } else {
            header('HTTP/1.O 401 Unauthorized');
            $data =
               [
                  'state' => 'error',
                  'title' => 'Unauthorized',
                  'msg' => 'No tienes permiso de acceder a este recurso.'
               ];
            echo json_encode($data);
            return false;
         }
      } catch (SignatureInvalidException $e) {
         header('HTTP/1.O 400 Bad Request');
         $data =
            [
               'state' => 'error',
               'title' => 'Unauthorized',
               'msg' => 'Acceso denegado. El token no es válido.'
            ];
         echo json_encode($data);
         return false;
      } catch (ExpiredException $e) {
         header('HTTP/1.O 400 Bad Request');
         $data =
            [
               'state' => 'error',
               'title' => 'Unauthorized',
               'msg' => 'El token ha caducado. Vuelva a iniciar sesión.'
            ];
         echo json_encode($data);
         return false;
      }
   } else {
      header('HTTP/1.O 400 Bad Request');
      $data =
         [
            'state' => 'error',
            'title' => 'Unauthorized',
            'msg' => 'No se ha proporcionado un token en la petición.'
         ];
      echo json_encode($data);
      return false;
   }
}

function isAuth()
{
   if (!empty($_SERVER["HTTP_AUTHORIZATION"])) {
      $token = $_SERVER['HTTP_AUTHORIZATION'];
      $key = JWTConfig()->secret;
      $algorithm = JWTConfig()->algorithm;
      try {
         if (JWT::decode($token, new Key($key, $algorithm))) return true;
      } catch (SignatureInvalidException $e) {
         header('HTTP/1.O 400 Bad Request');
         $data =
            [
               'state' => 'error',
               'title' => 'Unauthorized',
               'msg' => 'Acceso denegado. El token no es válido.'
            ];
         echo json_encode($data);
         return false;
      } catch (ExpiredException $e) {
         header('HTTP/1.O 400 Bad Request');
         $data =
            [
               'state' => 'error',
               'title' => 'Unauthorized',
               'msg' => 'El token ha caducado. Vuelva a iniciar sesión.'
            ];
         echo json_encode($data);
         return false;
      }
   } else {
      header('HTTP/1.O 400 Bad Request');
      $data =
         [
            'state' => 'error',
            'title' => 'Unauthorized',
            'msg' => 'No se ha proporcionado un token en la petición.'
         ];
      echo json_encode($data);
      return false;
   }
}

function generateToken($data)
{

   $key = JWTConfig()->secret;
   $algorithm = JWTConfig()->algorithm;
   $payload = [
      'iat' => JWTConfig()->iat,
      'exp' => JWTConfig()->exp,
      'usuario' => $data
   ];

   $token = JWT::encode($payload, $key, $algorithm);

   return $token;
}

function decodeToken($token)
{
   $key = JWTConfig()->secret;
   $algorithm = JWTConfig()->algorithm;
   $data = JWT::decode($token, new Key($key, $algorithm));

   return $data;
}
