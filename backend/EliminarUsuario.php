<?php
require_once './clases/Usuario.php';
if (isset($_POST['id']) && isset($_POST['accion'])) {
  $id = $_POST['id'];
  $accion = $_POST['accion'];
  $exito = Usuario::Eliminar($id);
  if ($exito) {
    $mensaje = "Usuario eliminado";
  } else {
    $mensaje = "El usuario no existe";
  }
  $jsonResponse = ["exito" => $exito, "mensaje" => $mensaje];
  echo json_encode($jsonResponse, JSON_PRETTY_PRINT);
}