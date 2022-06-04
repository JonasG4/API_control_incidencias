<?php
require_once('../config/database.php');


function getAllIncidents()
{
    $conn = connect();
    $select_data = "SELECT id_incidente, tipo, descripcion, fecha_ingreso, imagen, id_usuario, estado, nota FROM incidencias";

    $query = mysqli_query($conn, $select_data);

    $nRow = mysqli_num_rows($query);

    if ($nRow != 0) {
        while ($incidents = mysqli_fetch_array($query)) {
            $jsonRow = array();

            $id_incidente = $incidents['id_incidente'];
            $tipo = $incidents['tipo'];
            $descripcion = $incidents['descripcion'];
            $fecha_ingreso = $incidents['fecha_ingreso'];
            $imagen = $incidents['imagen'];
            $id_usuario = $incidents['id_usuario'];
            $estado = $incidents['estado'];
            //Asignar 
            $jsonRow['id_incidente'] = $id_incidente;
            $jsonRow['tipo'] = $tipo;
            $jsonRow['descripcion'] = $descripcion;
            $jsonRow['fecha_ingreso'] = $fecha_ingreso;
            $jsonRow['imagen'] = $imagen;
            $jsonRow['id_usuario'] = $id_usuario;
            $jsonRow['estado'] = $estado;
            $row = $jsonRow;
        }
        disconnect($conn);
        return array_values($row);
    } else {
        $row = "No existen registros";
        disconnect($conn);
        return $row;
    }
}

function getIncidentById($id)
{
    $conn = connect();
    $row = array();
    $searchById = "SELECT incidencias.id_incidente,incidencias.tipo,incidencias.descripcion,incidencias.fecha_ingreso,incidencias.imagen,incidencias.estado,incidencias.nota,usuarios.id_usuario,usuarios.nombre,usuarios.apellido,usuarios.email FROM incidencias INNER JOIN usuarios ON incidencias.id_usuario = usuarios.id_usuario WHERE id_incidente = '$id' ";
    $query = mysqli_query($conn, $searchById);
    $nRow = mysqli_num_rows($query); 
    if ($nRow != 0) {
        while ($incident = mysqli_fetch_array($query)) {

            $jsonRow = array();
            //asignar
            
            $id_incidente = $incident['id_incidente'];
            $tipo = $incident['tipo'];
            $descripcion = $incident['descripcion'];
            $fecha_ingreso = $incident['fecha_ingreso'];
            $imagen = $incident['imagen'];
            $usuario = [
                'id_usuario' => $incident['id_usuario'],
                'nombre' => $incident['nombre'],
                'apellido' => $incident['apellido'],
                'email' => $incident['email']
            ];
            $estado = $incident['estado'];
            $nota = $incident['nota'];

            //Asignar 
            $jsonRow['id_incidente'] = $id_incidente;
            $jsonRow['tipo'] = $tipo;
            $jsonRow['descripcion'] = $descripcion;
            $jsonRow['fecha_ingreso'] = $fecha_ingreso;
            $jsonRow['imagen'] = $imagen;
            $jsonRow['usuario'] = $usuario;
            $jsonRow['estado'] = $estado;
            $jsonRow['nota'] = $nota;
            $row[] = $jsonRow;
        }
        return array_values($row);
    } else {
        header('HTTP/1.O 404 Not Found');
        $row = 'Incidente no encontrado';
    }
    disconnect($conn);
}

function addNewIncident($incidente)
{
    $incidente = (object) $incidente;

    $conn = connect();

    $query = "INSERT INTO incidentes (id_incidente, tipo, descripcion, fecha_ingreso, imagen, id_usuario, estado, nota)
     VALUES('$incidente->id_incidente', '$incidente->tipo', '$incidente->descripcion', '$incidente->fecha_ingreso', '$incidente->imagen', '$incidente->id_usuario', '$incidente->estado', '$incidente->nota')";

    mysqli_query($conn, $query);

    disconnect($conn);
    return true;
}

function updateIncident($incidente)
{
    $incidente = (object) $incidente;

    $conn = connect();

    $query = "UPDATE incidentes SET tipo='$incidente->tipo', descripcion='$incidente->descripcion', imagen='$incidente->imagen' WHERE id_incidente='$incidente->id_incidente'";

    mysqli_query($conn, $query);

    disconnect($conn);
    return true;
}

function updateIncidentAdmin($incidente)
{
    $incidente = (object) $incidente;
    $conn = connect();

    $query = "UPDATE incidencias SET estado='$incidente->estado', nota='$incidente->nota' WHERE id_incidente='$incidente->id_incidente'";

    try {
        mysqli_query($conn, $query);
    }catch (Exception $e) {
        echo "Error al actualizar " . $e->getMessage();
        return false;
    }
    disconnect($conn);
    return true;
}

function deleteIncident($id_incidente)
{

    $conn = connect();

    $query = "DELETE FROM incidentes WHERE id_incidente='$id_incidente";

    mysqli_query($conn, $query);

    disconnect($conn);
    return true;
}

function getIncidentsByState($estado)
{
    $conn = connect();
    $row = array();
    $query = "SELECT id_incidente, tipo, descripcion, fecha_ingreso, id_usuario, imagen, estado, nota FROM incidentes WHERE estado = '$estado'";

    $response = mysqli_query($conn, $query);
    $nRow = mysqli_num_rows($response);

    if ($nRow != 0) {
        while ($incidents = mysqli_fetch_array($response)) {
            $jsonRow = array();

            //Asignar 
            $jsonRow['id_incidente'] = $incidents['id_incidente'];
            $jsonRow['tipo'] = $incidents['tipo'];
            $jsonRow['descripcion'] = $incidents['descripcion'];
            $jsonRow['fecha_ingreso'] = $incidents['fecha_ingreso'];
            $jsonRow['imagen'] = $incidents['imagen'];
            $jsonRow['id_usuario'] = $incidents['id_usuario'];
            $jsonRow['estado'] = $incidents['estado'];
            $jsonRow['nota'] = $incidents['nota'];
            $row = $jsonRow;
        }
        disconnect($conn);
        return array_values($row);
    } else {
        $row = "No existen registros";
        disconnect($conn);
        return $row;
    }
}

function getIncidentsByUsers($id_usuario)
{
    $conn = connect();
    $row = array();
    $query = "SELECT id_incidente, tipo, descripcion, fecha_ingreso, id_usuario, imagen, estado, nota FROM incidentes WHERE id_usuario = '$id_usuario'";

    $response = mysqli_query($conn, $query);
    $nRow = mysqli_num_rows($response);

    if ($nRow != 0) {
        while ($incidents = mysqli_fetch_array($response)) {
            $jsonRow = array();

            //Asignar 
            $jsonRow['id_incidente'] = $incidents['id_incidente'];
            $jsonRow['tipo'] = $incidents['tipo'];
            $jsonRow['descripcion'] = $incidents['descripcion'];
            $jsonRow['fecha_ingreso'] = $incidents['fecha_ingreso'];
            $jsonRow['imagen'] = $incidents['imagen'];
            $jsonRow['id_usuario'] = $incidents['id_usuario'];
            $jsonRow['estado'] = $incidents['estado'];
            $jsonRow['nota'] = $incidents['nota'];
            $row = $jsonRow;
        }
        disconnect($conn);
        return array_values($row);
    } else {
        $row = "No existen registros";
        disconnect($conn);
        return $row;
    }
}
function getIncidentById($id_incidente)
{
    $conn = connect();

    $query = "SELECT tipo, descripcion, imagen, id_usuario, estado, nota FROM incidentes WHERE id_incidente='$id_incidente'";

    try {
        $row = mysqli_query($conn, $query);
        $result = mysqli_fetch_assoc($row);
    } catch (Exception $e) {
        $result = "Error: " . $e->getMessage();
    }

    disconnect($conn);
    return $result;
}
