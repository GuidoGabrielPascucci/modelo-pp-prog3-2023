<?php

// ModificarEmpleado.php: Se recibirán por POST los siguientes valores: empleado_json (id, nombre, correo, clave, id_perfil, sueldo y pathFoto, en formato de cadena JSON) y foto (para modificar un empleado en la base de datos. Invocar al método Modificar. Nota: El valor del id, será el id del empleado 'original', mientras que el resto de los valores serán los del empleado modificado. Se retornará un JSON que contendrá: éxito(bool) y mensaje(string) indicando lo acontecido.

require_once './clases/Empleado.php';
$empleado_json = file_get_contents('php://input');
$obj = json_decode($empleado_json);

$empleado = new Empleado();
$empleado->id = $obj->id;
$empleado->nombre = $obj->nombre;
$empleado->correo = $obj->correo;
$empleado->clave = $obj->clave;
$empleado->id_perfil = $obj->id_perfil;
$empleado->sueldo = $obj->sueldo;
$obj->pathFoto;

// $empleado->foto = ;
// $empleado->foto = $_FILES['foto'];


$exito = $empleado->Modificar();

if ($exito) {
  $mensaje = "Usuario modificado exitosamente";
} else {
  $mensaje = "El usuario no existe";
}

$jsonResponse = ["exito" => $exito, "mensaje" => $mensaje];
echo json_encode($jsonResponse, JSON_PRETTY_PRINT);