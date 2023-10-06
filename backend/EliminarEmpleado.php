<?php

// EliminarEmpleado.php: Recibe el parámetro id por POST y se deberá borrar el empleado (invocando al método Eliminar).
// Se retornará un JSON que contendrá: éxito(bool) y mensaje(string) indicando lo acontecido.

require_once './clases/Empleado.php';

if (isset($_POST['id'])) {
  
  $id = $_POST['id'];
  $exito = Empleado::Eliminar($id);
  
  if ($exito) {
    $mensaje = "Empleado eliminado";
  } else {
    $mensaje = "El empleado no existe";
  }
  
  $jsonResponse = ["exito" => $exito, "mensaje" => $mensaje];
  echo json_encode($jsonResponse, JSON_PRETTY_PRINT);
}