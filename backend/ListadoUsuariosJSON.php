<?php
// ListadoUsuariosJSON.php: (GET) Se mostrará el listado de todos los usuarios en formato JSON.
require_once './clases/usuario.php';
$array_usuarios = Usuario::TraerTodosJSON();
echo json_encode($array_usuarios, JSON_PRETTY_PRINT);