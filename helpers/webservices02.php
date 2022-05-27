<?php
require_once("config.php");
$data = array();
$action = "";

if(isset($_POST["action"])){
    $action = $_POST["action"];
}

if($action == "insert"){
    $nombre = $_POST["nombre"];
    $telefono = $_POST["telefono"];

    if(addContact($nombre, $telefono)==1){
        $data["status"] = 201;
        $data["result"] = "Contacto guardado con éxito!";
    }else{
        $data["status"]= 400;
        $data["result"] = "Error, no se pudo guardar el contacto. Intente de nuevo";
    }
}else if($action == "search"){
    if(isset($_POST["filtro"])){
        $filtro = $_POST["filtro"];
    }
        $data["status"] = 200;
        $data["result"] = getAllContactsBy($filtro);
}
else if($action == "update"){
    $id_contact = $_POST["id_contacto"];
    $name = $_POST["nombre"];
    $phone = $_POST["telefono"];

    if(updateContact($id_contact, $name, $phone)==1){
        $data["status"]=200;
        $data["result"]="Se actualizó el contacto.";
    }else{
        $data["status"]=400;
        $data["result"]="Error, no se pudo actualizar el contacto.";
    }

}else if($action == "delete"){
    $id_contact = $_POST["id_contacto"];

    if(deleteContact($id_contact)==1){
        $data["status"]=200;
        $data["result"]="Se eliminó el contacto.";
    }else{
        $data["status"]=400;
        $data["result"]="Error, no se pudo eliminar el contacto.";
    }
}

echo json_encode($data);