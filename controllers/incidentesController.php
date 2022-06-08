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

if($action == 'get_incident') {
    $id = $_POST["id"];
    $data["result"] = getIncidentById($id);
    echo json_encode($data);
}

if($action == 'create'){

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
if ($action == 'create' && isAuth()) {

    $_FILES['imagen'];

    $incidente = [
        'tipo' => isset($_POST['tipo']) ? trim($_POST['tipo']) : '',
        'descripcion' => isset($_POST['descripcion']) ? trim($_POST['descripcion']) : '',
        'fecha_ingreso' => isset($_POST['fecha_ingreso']) ? trim($_POST['fecha_ingreso']) : '',
        'i'

    ];
}
//UPDATE -- ADMIN
//Estado, nota

//DELETE
if ($action === 'delete' && isAuth()) {
    if (isset($_POST['id_incidente'])) {

        $id_incidente= trim($_POST['id_incidente']);
        $errors = deleteValidation($id_incidente);

        if (count($errors) > 0) {
            header('HTTP/1.O 400 Bad Request');
            $data = [
                'state' => 'error',
                'title' => 'Errores de validación',
                'validationError' => $errors
            ];
        } else {

            try {
                deleteIncident($id_incidente);
                $data = [
                    'state' => 'success',
                    "title" => "Registro elimnado exitosamente.",
                    "msg" => "Se ha eliminado el incidente con id: {$id_incidente}"
                ];
            } catch (Exception $e) {
                header('HTTP/1.O 500 Internal Error');
                $data = [
                    'state' => 'error',
                    "title" => "SQL EXCEPCTION: Error en la consulta",
                    "msg" => $e->getMessage()
                ];
            }
        }
    } else {
        header('HTTP/1.O 400 Bad Request');
        $data = [
            'state' => 'error',

            'title' => 'Id de incidente no fue proporcionado',
            'msg' => 'Es necesario el id de incidente para eliminar el registro especifico.'
        ];
    }


    echo json_encode($data);
}
//Obtener un solo incidente

//Filter 
if ($action === 'search' && isAuth()) {
    $filter =  trim($_POST['filter']);

    try {
        $incidente = filterIncidents($filter);
        $data = [
            'state' => 'success',
            'title' => 'Incidentes filtrados con éxito. Cantidad: ' . count($incidente) . ' incidente(s)',
            'incidentes' => $incidente
        ];
    } catch (Exception $e) {
        header('HTTP/1.O 500 Internal Error');
        $data = [
            'state' => 'error',
            "title" => "SQL EXCEPCTION: Error en la consulta",
            "msg" => $e->getMessage()
        ];
    }

    echo json_encode($data);
}
