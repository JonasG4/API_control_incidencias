<?php
//Utils
require_once('../utils/auth.php');
require_once('../utils/validations/incidentesValidation.php');

//MODELOS
require_once('../models/incidente.php');

//INICIALIZAR VARIABLES
$data = [];

$action = '';

//EJECUTAR CONSULTAS
if (isset($_POST['action'])) {
    $action = strtolower($_POST['action']);
}

// ========== ACCIONES ============================= 

if ($action == 'listar') {
    $data["status"] = 200;
    $data["incidentes"] = getAllIncidents();
    echo json_encode($data);
}

if($action == 'get_incident') {
    $id = $_POST["id"];
    $data = getIncidentById($id);
    echo json_encode($data[0]);
}




//Create

//UPDATE -- usuario
// tipo, descripcion, imagen





//UPDATE -- ADMIN
//Estado, nota

//DELETE

//Obtener un solo incidente

//Filter 
//Tipo, estado, id_usuario
