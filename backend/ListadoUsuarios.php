<?php
require_once './clases/Usuario.php';
$usuarios = Usuario::TraerTodos();
if ($usuarios) {
  echo generarTabla($usuarios);
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

    $tableRow = "<tr style='height:60px;border:1px solid gray'>";

    foreach ($array_atributos as $atributo) {
      $td = "<td style='border:1px solid gray'>$atributo</td>";
      $tableRow .= $td;
    }

    $tableRow .= "</tr>";
    $rows .= $tableRow;
  }

  return $rows;
}