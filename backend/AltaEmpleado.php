<?php

/* AltaEmpleado.php: Se recibirán por POST todos los valores: nombre, correo, clave, id_perfil, sueldo y foto para registrar un empleado en la base de datos. Se retornará un JSON que contendrá: éxito(bool) y mensaje(string) indicando lo acontecido.*/

require_once './clases/Empleado.php';

$nombre = $_POST['nombre'];
$correo = $_POST['correo'];
$clave = $_POST['clave'];
$id_perfil = $_POST['id_perfil'];
$sueldo = $_POST['sueldo'];
$foto = $_FILES['foto'];

$empleado = new Empleado();
$empleado->nombre = $nombre;
$empleado->correo = $correo;
$empleado->clave = $clave;
$empleado->id_perfil = $id_perfil;
$empleado->sueldo = $sueldo;
$empleado->foto = $foto;

$exito = $empleado->Agregar();

if ($exito) {
  $mensaje = "Empleado agregado exitosamente";
} else {
  $mensaje = "Error: el empleado no ha podido ser agregado";
}

$jsonResponse = ["exito" => $exito, "mensaje" => $mensaje];
echo json_encode($jsonResponse, JSON_PRETTY_PRINT);