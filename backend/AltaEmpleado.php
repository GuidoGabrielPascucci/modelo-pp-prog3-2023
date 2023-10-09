<?php
require_once './clases/Empleado.php';
$nombre = $_POST['nombre'];
$correo = $_POST['correo'];
$clave = $_POST['clave'];
$id_perfil = $_POST['id_perfil'];
$sueldo = $_POST['sueldo'];
$foto = $_FILES['foto'];
$empleado = new Empleado($nombre, $correo, $clave, $id_perfil, null, $sueldo, $foto, null);
$exito = $empleado->Agregar();
if ($exito) {
  $mensaje = "Empleado agregado exitosamente";
} else {
  $mensaje = "Error: el empleado no ha podido ser agregado";
}
$jsonResponse = ["exito" => $exito, "mensaje" => $mensaje];
echo json_encode($jsonResponse, JSON_PRETTY_PRINT);