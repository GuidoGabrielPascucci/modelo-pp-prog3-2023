<?php
require_once './clases/usuario.php';

// ListadoUsuariosJSON.php: (GET) Se mostrará el listado de todos los usuarios en formato JSON.

$array_usuarios = Usuario::TraerTodosJSON();
foreach ($array_usuarios as $usuario) {
  echo "$usuario->nombre - $usuario->correo - $usuario->clave\n";
}