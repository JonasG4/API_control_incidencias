<?php
//CONFIG DATABASE
require_once('../config/database.php');

function getAllUsers() //1
{
  $conn = connect();
  $row = array();
  $select_data = "SELECT id_usuario,nombre,apellido,email,id_rol FROM usuarios";

  $query = mysqli_query($conn, $select_data);
  $nRow = mysqli_num_rows($query);

  if($nRow != 0) {
    while($users = mysqli_fetch_array($query)) {
      $jsonRow = array();
      $id_user = $users["id_usuario"];
      $name = $users["nombre"];
      $last_name = $users["apellido"];
      $email = $users["email"];
      $id_rol = $users["id_rol"];

      $jsonRow["id_usuario"]= $id_user;
      $jsonRow["nombre"]= $name;
      $jsonRow["apellido"]= $last_name;
      $jsonRow["email"]= $email;
      $jsonRow["id_rol"]= $id_rol;

      $row[] = $jsonRow;
    }
  }

  disconnect($conn);

  return array_values($row);
}

function getUserByEmail($email) //2
{
}

function addUser($user) 
{

}

function updateUser($user) 
{

}

function deleteUser($id) //5
{
}

function filterUsers($filter) //6
{

}
