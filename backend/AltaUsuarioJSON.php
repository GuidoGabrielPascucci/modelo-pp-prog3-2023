<?php
require_once './clases/Usuario.php';
$nombre = $_POST['nombre'];
$correo = $_POST['correo'];
$clave = $_POST['clave'];
$usuario = new Usuario($nombre, $correo, $clave);
echo $usuario->GuardarEnArchivo();