<?php
require_once './clases/Empleado.php';
$empleados = Empleado::TraerTodos();
if ($empleados) {
  echo generarTabla($empleados);
} else {
  echo "No se ha podido traer la información, disculpe las molestias ocasionadas";
}

function generarTabla($data) {

  $table_style = "'width:80%;margin:20px auto;border:5px solid black;font-size:25px;font-family:system-ui;text-align:center;border-spacing:0px;font-weight:400;border-collapse:collapse;'";
  $thead_style = "'font-size:33px;color:#f0f0f0;background-color:#0064a9;height:100px'";
  $tbody_style = "'background-color:#eeeeee'";

  function th_style($px) {
    return "style='min-width:$px\\px;'";
  }

  $rows = cargarDatos($data);

  $table = "
  <table style=$table_style>
    <thead style=$thead_style>
      <tr>
        <th " . th_style(70) . ">ID</th>
        <th " . th_style(200) . ">Nombre</th>
        <th " . th_style(200) . ">Correo</th>
        <th " . th_style(170) . ">ID Perfil</th>
        <th " . th_style(200) . ">Perfil</th>
        <th " . th_style(70) . ">Sueldo</th>
        <th " . th_style(70) . ">Foto</th>
      </tr>
    </thead>
    <tbody style=$tbody_style>
      $rows
    </tbody>
  </table>
  ";

  return $table;
}

function cargarDatos($empleados) {

  $rows = "";

  foreach ($empleados as $empleado) {

    $array_atributos = array(
      "id" => $empleado->id,
      "nombre" => $empleado->nombre,
      "correo" => $empleado->correo,
      "id_perfil" => $empleado->id_perfil,
      "perfil" => $empleado->perfil,
      "sueldo" => $empleado->sueldo,
      "foto" => $empleado->foto
    );

    $tableRow = "<tr style='height:60px;border:1px solid gray'>";

    foreach ($array_atributos as $atributo_key => $atributo_value) {
      $td = "<td style='border:1px solid gray'>";
      if ($atributo_key != 'foto') {
        $td .= $atributo_value;
      } else {
        $td .= "<img src='$atributo_value' style='width:200px;height:200px;object-fit:cover;'>";
      }
      $td .= "</td>";
      $tableRow .= $td;
    }

    $tableRow .= "</tr>";
    $rows .= $tableRow;
  }

  return $rows;
}