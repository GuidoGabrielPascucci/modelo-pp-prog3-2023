<?php
require_once './clases/Usuario.php';
$usuario_json = file_get_contents('php://input');
$obj = json_decode($usuario_json);
$nombre = $obj->nombre;
$correo = $obj->correo;
$clave = $obj->clave;
$id_perfil = $obj->id_perfil;
$id = $obj->id;
$usuario = new Usuario($nombre, $correo, $clave, $id_perfil, null, $id);
$exito = $usuario->Modificar();
if ($exito) {
  $mensaje = "Usuario modificado exitosamente";
} else {
  $mensaje = "El usuario no existe";
}
$jsonResponse = ["exito" => $exito, "mensaje" => $mensaje];
echo json_encode($jsonResponse, JSON_PRETTY_PRINT);