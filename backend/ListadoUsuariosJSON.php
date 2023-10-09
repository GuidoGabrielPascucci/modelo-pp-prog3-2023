<?php
require_once './clases/Usuario.php';
$array_usuarios = Usuario::TraerTodosJSON();
if ($array_usuarios) {
  echo json_encode($array_usuarios, JSON_PRETTY_PRINT);
} else {
  echo "Error: no se ha encontrado el recurso";
}