<?php
require_once('../config/database.php');


function getAllIncidents()
{
    $conn = connect();
    $row = array();
    $select_data = "SELECT id_incidente, tipo, descripcion, fecha_ingreso, imagen, id_usuario, estado, nota FROM incidentes";

    $query = mysqli_query($conn, $select_data);

    $nRow = mysqli_num_rows($query);

    if ($nRow != 0) {
        while ($incidents = mysqli_fetch_array($query)) {
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