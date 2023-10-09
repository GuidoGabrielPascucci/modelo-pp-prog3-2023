<?php
require_once './clases/Empleado.php';
$empleado_json = $_POST['empleado_json'];
$data = json_decode($empleado_json, true);
$empleado = new Empleado($data['nombre'], $data['correo'], $data['clave'], $data['id_perfil'], null, $data['sueldo'], $_FILES['foto'], $data['id']);
$exito = $empleado->Modificar();
if ($exito) {
  $mensaje = "Usuario modificado exitosamente";
} else {
  $mensaje = "El usuario no existe";
}
$jsonResponse = ["exito" => $exito, "mensaje" => $mensaje];
echo json_encode($jsonResponse, JSON_PRETTY_PRINT);