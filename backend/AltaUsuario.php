<?php
// AltaUsuario.php: Se recibe por POST el correo, la clave, el nombre y el id_perfil. Se invocará al método Agregar. Se retornará un JSON que contendrá: éxito(bool) y mensaje(string) indicando lo acontecido.
require_once './clases/usuario.php';

$nombre = $_POST['nombre'];
$correo = $_POST['correo'];
$clave = $_POST['clave'];
$id_perfil = $_POST['id_perfil'];

$usuario = new Usuario();
$usuario->nombre = $nombre;
$usuario->correo = $correo;
$usuario->clave = $clave;
$usuario->id_perfil = $id_perfil;

$resultado = $usuario->Agregar();
$obj = new stdClass();

if ($resultado) {
  $obj->mensaje = "Usuario agregado exitosamente";
} else {
  $obj->mensaje = "El usuario no ha sido agregado debido a un error del sistema";
}

$obj->exito = $resultado;
$json = json_encode($obj, JSON_PRETTY_PRINT); 

echo $json != false ? $json : "No se ha podido generar la respuesta";