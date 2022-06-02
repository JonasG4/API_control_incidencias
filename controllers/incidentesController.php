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




//Create

//UPDATE -- usuario
// tipo, descripcion, imagen





//UPDATE -- ADMIN
//Estado, nota

//DELETE
if ($action === 'eliminar' && isAuth()) {
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
if ($action === 'buscar' && isAuth()) {
    $filter =  trim($_POST['filter']);

    try {
        $incidente = filterIncident($filter);
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
//Tipo, estado, id_usuario
