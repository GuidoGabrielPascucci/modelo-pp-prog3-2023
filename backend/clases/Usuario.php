<?php
require_once 'IBM.php';

class Usuario implements IBM
{
  //public int $id;
  //public string $nombre;
  //public string $correo;
  //public string $clave;
  //public int $id_perfil;
  //public string $perfil;

  public function __construct($nombre = null, $correo = null, $clave = null, $id_perfil = null, $perfil = null, $id = null) {
    $this->nombre = $nombre;
    $this->correo = $correo;
    $this->clave = $clave;
    $this->id_perfil = $id_perfil;
    $this->perfil = $perfil;
    $this->id = $id;
  }

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
    $resultado = false;
    $filename = "../backend/archivos/usuarios.json";
    $nuevoUsuario_dataJson = $this->ToJSON();
    $contenido_a_escribir = $nuevoUsuario_dataJson;
    if (file_exists($filename)) {
      $json_content = file_get_contents($filename);
      $array_usuarios = json_decode($json_content);
      $nuevoUsuario_dataObject = json_decode($nuevoUsuario_dataJson);
      array_push($array_usuarios, $nuevoUsuario_dataObject);
      $contenido_a_escribir = json_encode($array_usuarios, JSON_PRETTY_PRINT);
      $resultado = file_put_contents($filename, $contenido_a_escribir);
    }
    $mensaje = "";
    if ($resultado) {
      $mensaje = "Usuario agregado exitosamente";
    } else {
      $mensaje = "Error! Ha habido un fallo y el usuario no ha sido agregado";
    }
    $obj = new stdClass();
    $obj->mensaje = $mensaje;
    $obj->exito = $resultado != false;
    return json_encode($obj);
  }

  // Retornará un array de objetos de tipo Usuario, recuperado del archivo Usuarios.json
  public static function TraerTodosJSON()
  {
    $filename = "../backend/archivos/usuarios.json";
    if (!file_exists($filename)) {
      return false;
    }
    $usuarios = array();
    $json_content = file_get_contents($filename);
    $data = json_decode($json_content);
    foreach ($data as $obj) {
      $nombre = $obj->nombre;
      $correo = $obj->correo;
      $clave = $obj->clave;
      $usuario = new Usuario($nombre, $correo, $clave);
      array_push($usuarios, $usuario);
    }
    return $usuarios;
  }

  // Agrega, a partir de la instancia actual, un nuevo registro en la tabla usuarios (id,nombre, correo, clave e id_perfil), de la base de datos usuarios_test. Retorna true, si se pudo agregar, false, caso contrario.
  public function Agregar()
  {
    $returnValue = false;
    $dbname = "usuarios_test";
    $host = "localhost";
    $dsn = "mysql:host=$host;dbname=$dbname";
    $user = "root";
    $pw = "root404";

    try {
      $pdo = new PDO($dsn, $user, $pw);
      $table = "usuarios";
      $columns = "nombre, correo, clave, id_perfil";
      $values = ":nombre, :correo, :clave, :id_perfil";
      $query = "INSERT INTO `$table` ($columns) VALUES ($values)";
      $pdoStmt = $pdo->prepare($query);

      if ($pdoStmt) {
        $params = array(
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
          ]
        );

        foreach ($params as $paramKey => $paramValue) {
          $pdoStmt->bindParam(":$paramKey", $paramValue["value"], $paramValue["type"]);
        }

        $result = $pdoStmt->execute();

        if ($result) {
          $returnValue = true;
        }

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
    $pw = "root404";

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
    $pw = "root404";
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
              $arr = array();
              $usuario = new Usuario();
              foreach ($user_data as $user_key => $user_value) {
                $usuario->$user_key = $user_value;
              }
              $usuario->perfil = $perfil_data['descripcion'];
              $returnValue = $usuario;
            }
          }
        }
      }
    } catch (PDOException $err) {
      echo $err->getMessage();
    }

    return $returnValue;
  }

  // Modifica en la base de datos el registro coincidente con la instancia actual (comparar por id). Retorna true, si se pudo modificar, false, caso contrario.
  public function Modificar()
  {
    $returnValue = false;
    $dbname = "usuarios_test";
    $host = "localhost";
    $dsn = "mysql:host=$host;dbname=$dbname";
    $user = "root";
    $pw = "root404";

    try {
      $pdo = new PDO($dsn, $user, $pw);
      $table = "usuarios";
      $set = "nombre=:nombre,correo=:correo,clave=:clave,id_perfil=:id_perfil";
      $query = "UPDATE $table SET $set WHERE id=:id";
      $pdoStmt = $pdo->prepare($query);

      if ($pdoStmt) {

        $params = array(
          "nombre" => [
            "value" => $this->nombre,
            "type" => PDO::PARAM_STR,
            "maxLength" => 30
          ],
          "correo" => [
            "value" => $this->correo,
            "type" => PDO::PARAM_STR,
            "maxLength" => 50
          ],
          "clave" => [
            "value" => $this->clave,
            "type" => PDO::PARAM_STR,
            "maxLength" => 8
          ],
          "id_perfil" => [
            "value" => $this->id_perfil,
            "type" => PDO::PARAM_INT,
            "maxLength" => 10
          ],
          "id" => [
            "value" => $this->id,
            "type" => PDO::PARAM_INT,
            "maxLength" => 10
          ]
        );
        
        foreach ($params as $paramKey => $paramValue) {
          $pdoStmt->bindParam(":$paramKey", $paramValue["value"], $paramValue["type"], $paramValue["maxLength"]);
        }

        $result = $pdoStmt->execute();

        if ($result && $pdoStmt->rowCount()) {
          $returnValue = true;
        }
      }
    } catch (PDOException $err) {
      echo $err->getMessage();
    }

    return $returnValue;
  }

  // Eliminar (estático): elimina de la base de datos el registro coincidente con el id recibido cómo parámetro. Retorna true, si se pudo eliminar, false, caso contrario.
  public static function Eliminar($id)
  {
    $returnValue = false;
    $dbname = "usuarios_test";
    $host = "localhost";
    $dsn = "mysql:host=$host;dbname=$dbname";
    $user = "root";
    $pw = "root404";

    try {
      $pdo = new PDO($dsn, $user, $pw);
      $table = "usuarios";
      $query = "DELETE FROM $table WHERE id=:id";
      $pdoStmt = $pdo->prepare($query);

      if ($pdoStmt) {
        $pdoStmt->bindParam(":id", $id, PDO::PARAM_INT, 10);
        $result = $pdoStmt->execute();

        if ($result && $pdoStmt->rowCount()) {
          $returnValue = true;
        }
      }
    } catch (PDOException $err) {
      echo $err->getMessage();
    }

    return $returnValue;
  }
}