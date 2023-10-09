<?php
require_once './clases/Usuario.php';
$nombre = $_POST['nombre'];
$correo = $_POST['correo'];
$clave = $_POST['clave'];
$id_perfil = $_POST['id_perfil'];
$usuario = new Usuario($nombre, $correo, $clave, $id_perfil);
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