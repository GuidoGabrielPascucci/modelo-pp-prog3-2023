<?php
require_once './clases/usuario.php';

// AltaUsuarioJSON.php: Se recibe por POST el correo, la clave y el nombre. Invocar al método
// GuardarEnArchivo.

$nombre = $_POST['nombre'];
$correo = $_POST['correo'];
$clave = $_POST['clave'];

$usuario = new Usuario();
$usuario->nombre = $nombre;
$usuario->correo = $correo;
$usuario->clave = $clave;

echo $usuario->GuardarEnArchivo();