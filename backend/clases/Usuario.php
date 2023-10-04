<?php

class Usuario
{

  public int $id;
  public string $nombre;
  public string $correo;
  public string $clave;
  public int $id_perfil;
  public string $perfil;

  // function __construct($id, $nombre, $correo, $clave, $id_perfil, $perfil) {
  //   $this->id = $id;
  //   $this->nombre = $nombre;
  //   $this->correo = $correo;
  //   $this->clave = $clave;
  //   $this->id_perfil = $id_perfil;
  //   $this->perfil = $perfil;
  // }

  // Retornará los atributos nombre, correo y clave en formato JSON
  public function ToJSON()
  {
    $filename = "../backend/archivos/usuarios.json";
    if (!file_exists($filename)) {
      $arr = array(array("nombre" => $this->nombre, "correo" => $this->correo, "clave" => $this->clave));
    } else {
      $arr = array("nombre" => $this->nombre, "correo" => $this->correo, "clave" => $this->clave);
    }
    return json_encode($arr, JSON_PRETTY_PRINT);
  }

  // Agregará al usuario en ./backend/archivos/usuarios.json. Retornará un JSON que contendrá: éxito(bool) y mensaje(string) indicando lo acontecido.
  public function GuardarEnArchivo()
  {
    $filename = "../backend/archivos/usuarios.json";
    $nuevoUsuario_dataJson = $this->ToJSON();
    $contenido_a_escribir = $nuevoUsuario_dataJson;
    if (file_exists($filename)) {
      $json_content = file_get_contents($filename);
      $array_usuarios = json_decode($json_content);
      $nuevoUsuario_dataObject = json_decode($nuevoUsuario_dataJson);
      array_push($array_usuarios, $nuevoUsuario_dataObject);
      $contenido_a_escribir = json_encode($array_usuarios, JSON_PRETTY_PRINT);
    }
    $resultado = file_put_contents($filename, $contenido_a_escribir);
    $mensaje = "";
    if ($resultado) {
      $mensaje = "El usuario ha sido agregado con éxito!";
    } else {
      $mensaje = "Error! Ha habido un fallo y el usuario no ha sido agregado";
    }
    $obj = new stdClass();
    $obj->mensaje = $mensaje;
    $obj->exito = gettype($resultado) === 'int';
    return json_encode($obj);
  }

  // Retornará un array de objetos de tipo Usuario, recuperado del archivo usuarios.json
  public static function TraerTodosJSON()
  {
    $filename = "../backend/archivos/usuarios.json";
    $json_content = file_get_contents($filename);
    $data = json_decode($json_content);
    return $data;
  }

  // Agrega, a partir de la instancia actual, un nuevo registro en la tabla usuarios (id,nombre, correo, clave e id_perfil), de la base de datos usuarios_test. Retorna true, si se pudo agregar, false, caso contrario.
  public function Agregar()
  {
    $returnValue = false;
    $dbname = "usuarios_test";
    $host = "localhost";
    $dsn = "mysql:host=$host;dbname=$dbname";
    $user = "root";
    $pw = "";

    try {
      $pdo = new PDO($dsn, $user, $pw);
      $table = "usuarios";
      $columns = "nombre, correo, clave, id_perfil";
      $values = ":nombre, :correo, :clave, :id_perfil";
      $query = "INSERT INTO `$table` ($columns) VALUES ($values)";
      $pdoStatement_obj = $pdo->prepare($query);

      if ($pdoStatement_obj) {

        $params = array(

          // "id" => [
          //   "value" => "$this->id",
          //   "type" => PDO::PARAM_INT
          // ],

          "nombre" => [
            "value" => "$this->nombre",
            "type" => PDO::PARAM_STR
          ],

          "correo" => [
            "value" => "$this->correo",
            "type" => PDO::PARAM_STR
          ],

          "clave" => [
            "value" => "$this->clave",
            "type" => PDO::PARAM_STR
          ],

          "id_perfil" => [
            "value" => "$this->id_perfil",
            "type" => PDO::PARAM_INT
          ],

        );

        foreach ($params as $paramKey => $paramValue) {
          $pdoStatement_obj->bindParam(":$paramKey", $paramValue["value"], $paramValue["type"]);
        }

        $result = $pdoStatement_obj->execute();

        if ($result) {
          echo "Usuario agregado exitosamente";
          $returnValue = true;
        } else {
          echo "No se pudo realizar la acción de agregar el usuario";
        }

      } else {
        echo "Error, no se pudo generar la sentencia preparada!";
      }
    } catch (PDOException $err) {
      echo $err->getMessage();
    }

    return $returnValue;
  }

  // Retorna un array de objetos de tipo Usuario, recuperados de la base de datos (con la descripción del perfil correspondiente).
  public static function TraerTodos()
  {
    $returnValue = false;
    $dbname = "usuarios_test";
    $host = "localhost";
    $dsn = "mysql:host=$host;dbname=$dbname";
    $user = "root";
    $pw = "";

    try {
      $pdo = new PDO($dsn, $user, $pw);
      $array_resultSet = array();

      for ($i = 0; $i < 2; $i++) {
        switch ($i) {
          case 0:
            $table = "usuarios";
            break;
          case 1:
            $table = "perfiles";
            break;
        }
        $pdoStatement_obj = $pdo->query("SELECT * FROM `$table`");
        $resultSet = $pdoStatement_obj->fetchAll(PDO::FETCH_ASSOC);
        array_push($array_resultSet, $resultSet);
      }

      $resultSet_usuarios = $array_resultSet[0];
      $resultSet_perfiles = $array_resultSet[1];
      $array_usuarios = array();

      foreach ($resultSet_usuarios as $row) {
        $usuario = new Usuario();
        foreach ($row as $key => $value) {
          $usuario->$key = $value;
          if ($key === 'id_perfil') {
            foreach ($resultSet_perfiles as $perfil) {
              if ($perfil['id'] === $value) {
                $usuario->perfil = $perfil['descripcion'];
              }
            }
          }
        }
        array_push($array_usuarios, $usuario);
      }

      $returnValue = $array_usuarios;

    } catch (PDOException $err) {
      echo $err->getMessage();
    }

    return $returnValue;
  }

  // Retorna un objeto de tipo Usuario, de acuerdo al correo y clave que se reciben en el parámetro $params.
  public static function TraerUno($params)
  {
    $returnValue = false;
    $dbname = "usuarios_test";
    $host = "localhost";
    $dsn = "mysql:host=$host;dbname=$dbname";
    $user = "root";
    $pw = "";
    $correo = $params->correo;
    $clave = $params->clave;

    try {
      $pdo = new PDO($dsn, $user, $pw);
      $query = "SELECT * FROM `usuarios` WHERE `correo`=:correo AND `clave`=:clave";
      $pdoStmt = $pdo->prepare($query);
      $pdoStmt->bindParam(":correo", $correo, PDO::PARAM_STR);
      $pdoStmt->bindParam(":clave", $clave, PDO::PARAM_STR);
      $success = $pdoStmt->execute();
      if ($success) {
        $user_data = $pdoStmt->fetch(PDO::FETCH_ASSOC);
        if ($user_data) {
          $id_perfil = $user_data['id_perfil'];
          $query = "SELECT * FROM `perfiles` WHERE `id`=:id";
          $pdoStmt = $pdo->prepare($query);
          $pdoStmt->bindParam(":id", $id_perfil, PDO::PARAM_INT);
          $success = $pdoStmt->execute();
          if ($success) {
            $perfil_data = $pdoStmt->fetch(PDO::FETCH_ASSOC);
            if ($perfil_data) {
              $usuario = new Usuario();
              foreach ($user_data as $user_key => $user_value) {
                $usuario->$user_key = $user_value;
              }
              $usuario->perfil = $perfil_data['descripcion'];
              $returnValue = $usuario;
            } // else {
            //   echo "error.....\n\n";
            // }
          } // else {
          // echo "error.....\n\n";
          // }
        } // else {
        //   echo "error...\n\n";
        // }
      } // else {
      //   echo "error.....\n\n";
      // }
    } catch (PDOException $err) {
      echo $err->getMessage();
    }

    return $returnValue;
  }

}