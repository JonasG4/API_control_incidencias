<?php
//LIBRERIAS DE JWT
require_once('../vendor/autoload.php');
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

//INICIALIZAR VARIABLES
$data = array();
$action = '';

//MODELOS
require_once('../models/usuario.php');

//EJECUTAR CONSULTAS
if(isset($_POST['action'])){
    $action = $_POST['action'];
    echo getAllUsers();
}

if($action == 'registrar'){
    return print_r($_POST);
}