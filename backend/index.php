<?php
// include './clases/usuario.php';


$accion = $_REQUEST['accion'];


switch ($accion) {
  case 'traerTodos':
    // $array_usuarios = Usuario::TraerTodosJSON();
    // foreach ($array_usuarios as $usuario) {
    //   echo "$usuario->nombre - $usuario->correo - $usuario->clave\n";
    // }
    break;

  case 'agregar':
    // $id = $_POST['id'];
    // $nombre = $_POST['nombre'];
    // $correo = $_POST['correo'];
    // $clave = $_POST['clave'];
    // $id_perfil = $_POST['id_perfil'];
    // $perfil = $_POST['perfil'];
    // $usuario = new Usuario($id, $nombre, $correo, $clave, $id_perfil, $perfil);
    // echo $usuario->GuardarEnArchivo();
    break;

  default:

    break;
}