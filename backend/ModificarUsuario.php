<?php
// ModificarUsuario.php: Se recibirán por POST los siguientes valores: usuario_json (id, nombre, correo, clave y id_perfil, en formato de cadena JSON), para modificar un usuario en la base de datos. Invocar al método Modificar.
// Retornar un JSON que contendrá: éxito(bool) y mensaje(string) indicando lo acontecido.
require_once './clases/Usuario.php';
$usuario_json = file_get_contents('php://input');
$obj = json_decode($usuario_json);

$usuario = new Usuario();
$usuario->id = $obj->id;
$usuario->nombre = $obj->nombre;
$usuario->correo = $obj->correo;
$usuario->clave = $obj->clave;
$usuario->id_perfil = $obj->id_perfil;

$exito = $usuario->Modificar();

if ($exito) {
  $mensaje = "Usuario modificado exitosamente";
} else {
  $mensaje = "El usuario no existe";
}

$jsonResponse = ["exito" => $exito, "mensaje" => $mensaje];
echo json_encode($jsonResponse, JSON_PRETTY_PRINT);