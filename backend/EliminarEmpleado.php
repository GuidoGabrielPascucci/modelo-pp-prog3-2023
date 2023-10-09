<?php
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
} else {
  echo "No has enviado información, no se puede hacer nada";
}