<?php
// ListadoEmpleados.php: (GET) Se mostrará el listado completo de los empleados (obtenidos de la base de datos) en una tabla (HTML con cabecera). Invocar al método TraerTodos.
// Nota: preparar la tabla (HTML) con una columna extra para que muestre la imagen de la foto (50px X 50px).

require_once './clases/Empleado.php';

$empleados = Empleado::TraerTodos();
echo json_encode($empleados, JSON_PRETTY_PRINT);

die();

if ($empleados) {

  // echo generarTabla($usuarios);
} else {
  echo "No se ha podido traer la información, disculpe las molestias ocasionadas";
}

function generarTabla($data) {

  $table_style = "'width:80%;margin:20px auto;border:5px solid black;font-size:25px;font-family:system-ui;text-align:center;border-spacing:0px;font-weight:400'";
  $thead_style = "'font-size:33px;color:#f0f0f0;background-color:#0064a9;height:100px'";
  $tbody_style = "'background-color:#eeeeee'";

  $rows = cargarDatos($data);

  $table = "
  <table style=$table_style>
    <thead style=$thead_style>
      <tr>
        <th>ID</th>
        <th>Nombre</th>
        <th>Correo</th>
        <th>ID Perfil</th>
        <th>Perfil</th>
      </tr>
    </thead>
    <tbody style=$tbody_style>
      $rows
    </tbody>
  </table>
  ";

  return $table;
}

function cargarDatos($usuarios) {

  $rows = "";

  foreach ($usuarios as $usuario) {

    $array_atributos = array(
      $usuario->id,
      $usuario->nombre,
      $usuario->correo,
      $usuario->id_perfil,
      $usuario->perfil
    );

    $tableRow = "<tr>";

    foreach ($array_atributos as $atributo) {
      $td = "<td>$atributo</td>";
      $tableRow .= $td;
    }

    $tableRow .= "</tr>";
    $rows .= $tableRow;
  }

  return $rows;
}