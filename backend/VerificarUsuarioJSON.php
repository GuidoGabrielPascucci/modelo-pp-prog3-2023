<?php
require_once './clases/Usuario.php';
$usuario_json = file_get_contents('php://input');
$obj = json_decode($usuario_json);
$usuario = Usuario::TraerUno($obj);
if ($usuario) {
  $mensaje = "Usuario verificado exitosamente";
} else {
  $mensaje = "El usuario no existe";
}
$exito = $usuario ? true : false;
$jsonResponse = ["exito" => $exito, "mensaje" => $mensaje];
echo json_encode($jsonResponse, JSON_PRETTY_PRINT);