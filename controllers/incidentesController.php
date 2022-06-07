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

if ($action == 'list') {
    $data["status"] = 200;
    $data["incidentes"] = getAllIncidents();
    echo json_encode($data);
}

//Create
if ($action == 'create' && isAuth()) {

    $_FILES['imagen'];

    $incidente = [
        'tipo' => isset($_POST['tipo']) ? trim($_POST['tipo']) : '',
        'descripcion' => isset($_POST['descripcion']) ? trim($_POST['descripcion']) : '',
        'fecha_ingreso' => isset($_POST['fecha_ingreso']) ? trim($_POST['fecha_ingreso']) : '',
        'i'

    ];

}
