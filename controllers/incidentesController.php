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
    $data["result"] = getIncidentById($id);
    echo json_encode($data);
}

if($action === 'update_admin') {
    if(isset($_POST['id_incidente'])) {
        $incidente = [
            'id_incidente' => trim($_POST['id_incidente']),
            'estado' => isset($_POST['estado']) ? trim($_POST['estado']) : '',
            'nota' => isset($_POST['nota']) ? trim($_POST['nota']) : ''
        ];

        $errors = updateValidationAdmin($incidente);

        if(count($errors)) {
            header('HTTP/1.O 400 Bad Request');
            $data = [
                'state' => 'error',
                'title' => 'Errores de validación',
                'validationError' => $errors
            ];
        }else {
            try {
                updateIncidentAdmin($incidente);
                $data = [
                    'state' => 'success',
                     'title' => 'Incidente actualizado con exitosamente',
                     'incidente' => $incidente
                ];
            } catch (Exception $e) {
               header('HTTP/1.O 500 Internal Error');
               $data = [
                   'state' => 'error',
                   'title' =>  'Error en la consulta',
                   'msg' => $e->getMessage()
               ];     
            }
        }
    }else {
        header('HTTP/1.O 400 Bad Request');
        $data = [
          'state' => 'error',
          'title' => 'Id de incidente no fue proporcionado',
          'msg' => 'Es necesario el id del incidente'
        ];
    }

    echo json_encode($data);
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
