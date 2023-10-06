<?php

require_once 'Usuario.php';
require_once 'ICRUD.php';

class Empleado extends Usuario implements ICRUD
{
  public array|string $foto;
  public float $sueldo;

  // Agrega, a partir de la instancia actual, un nuevo registro en la tabla empleados (id,nombre, correo, clave, id_perfil, foto y sueldo), de la base de datos usuarios_test. Retorna true, si se pudo agregar, false, caso contrario.
  // Nota: La foto guardarla en “./backend/empleados/fotos/”, con el nombre formado por el nombre punto tipo punto hora, minutos y segundos del alta (Ejemplo: juan.105905.jpg).
  public function Agregar(): bool
  {
    $returnValue = false;
    $dbname = "usuarios_test";
    $host = "localhost";
    $dsn = "mysql:host=$host;dbname=$dbname";
    $user = "root";
    $pw = "";

    try {
      $pdo = new PDO($dsn, $user, $pw);
      $stmt = $pdo->prepare("SELECT `descripcion` FROM `perfiles` WHERE `id`=:id_perfil");
      $stmt->bindParam(":id_perfil", $this->id_perfil, PDO::PARAM_INT);

      if ($stmt->execute()) {
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $stmt = null;
        $columns = "nombre, correo, clave, id_perfil, foto, sueldo";
        $values = ":nombre, :correo, :clave, :id_perfil, :foto, :sueldo";
        $query = "INSERT INTO `empleados` ($columns) VALUES ($values)";
        $stmt = $pdo->prepare($query);

        if ($stmt) {

          $upload_dir = "./empleados/fotos/";
          $date = new DateTime('now', new DateTimeZone('GMT-3'));
          $pathinfo = pathinfo($this->foto['name']);
          $name = $this->nombre;
          $type = $result['descripcion'];
          $time = $date->format('His');
          $extension = $pathinfo['extension'];
          $filename = "$name.$type.$time.$extension";
          $file_full_path = $upload_dir . $filename;
          $success = move_uploaded_file($this->foto['tmp_name'], $file_full_path);

          if ($success) {
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
              "foto" => [
                "value" => $file_full_path,
                "type" => PDO::PARAM_STR,
                "maxLength" => 50
              ],
              "sueldo" => [
                "value" => $this->sueldo,
                "type" => PDO::PARAM_STR,
                "maxLength" => null
              ]
            );
    
            foreach ($params as $paramKey => $paramValue) {
              $stmt->bindParam(":$paramKey", $paramValue["value"], $paramValue["type"], $paramValue["maxLength"]);
            }
    
            $result = $stmt->execute();
    
            if ($result) {
              $returnValue = true;
            }
          }
        }
      }

    } catch (PDOException $err) {
      echo $err->getMessage();
    }

    return $returnValue;
  }

  // Modifica en la base de datos el registro coincidente con la instancia actual (comparar por id). Retorna true, si se pudo modificar, false, caso contrario. | Nota: Si la foto es pasada, guardarla en “./backend/empleados/fotos/”, con el nombre formado por el nombre punto tipo punto hora, minutos y segundos del alta (Ejemplo: juan.105905.jpg). Caso contrario, sólo actualizar el campo de la base.
  public function Modificar(): bool
  {
    $returnValue = false;
    $dbname = "usuarios_test";
    $host = "localhost";
    $dsn = "mysql:host=$host;dbname=$dbname";
    $user = "root";
    $pw = "";

    try {
      $pdo = new PDO($dsn, $user, $pw);
      $table = "empleados";
      $set = "`nombre`=:nombre,`correo`=:correo,`clave`=:clave,`id_perfil`=:id_perfil,`foto`=:foto,`sueldo`=:sueldo";
      $query = "UPDATE `$table` SET $set WHERE `id`=:id";
      $pdoStmt = $pdo->prepare($query);

      if ($pdoStmt) {

        $params = array(
          "nombre" => [
            "value" => $this->nombre,
            "type" => PDO::PARAM_STR,
            "maxLenght" => 30
          ],
          "correo" => [
            "value" => $this->correo,
            "type" => PDO::PARAM_STR,
            "maxLenght" => 50
          ],
          "clave" => [
            "value" => $this->clave,
            "type" => PDO::PARAM_STR,
            "maxLenght" => 8
          ],
          "id_perfil" => [
            "value" => $this->id_perfil,
            "type" => PDO::PARAM_INT,
            "maxLenght" => 10
          ],
          "foto" => [
            "value" => $this->foto,
            "type" => PDO::PARAM_STR,
            "maxLenght" => 50
          ],
          "sueldo" => [
            "value" => $this->sueldo,
            "type" => PDO::PARAM_STR,
            "maxLenght" => null
          ],
          "id" => [
            "value" => $this->id,
            "type" => PDO::PARAM_INT,
            "maxLenght" => 10
          ]
        );

        foreach ($params as $paramKey => $paramValue) {
          $pdoStmt->bindParam(":$paramKey", $paramValue["value"], $paramValue["type"], $paramValue["maxLength"]);
        }

        $result = $pdoStmt->execute();

        if ($result && $pdoStmt->rowCount()) {
          $returnValue = true;
        }

      } else {
        echo "Error, no se pudo generar la sentencia preparada!";
      }

    } catch (PDOException $err) {
      echo $err->getMessage();
    }

    return $returnValue;
  }

  // Elimina de la base de datos el registro coincidente con el id recibido cómo parámetro. Retorna true, si se pudo eliminar, false, caso contrario.
  public static function Eliminar($id): bool
  {
    $returnValue = false;
    $dbname = "usuarios_test";
    $host = "localhost";
    $dsn = "mysql:host=$host;dbname=$dbname";
    $user = "root";
    $pw = "";

    try {
      $pdo = new PDO($dsn, $user, $pw);
      $table = "empleados";
      $query = "DELETE FROM `$table` WHERE `id`=:id";
      $pdoStmt = $pdo->prepare($query);

      if ($pdoStmt) {

        $pdoStmt->bindParam(":id", $id, PDO::PARAM_INT);
        $result = $pdoStmt->execute();

        if ($result && $pdoStmt->rowCount()) {
          $returnValue = true;
        }

      } else {
        echo "Error, no se pudo generar la sentencia preparada!";
      }

    } catch (PDOException $err) {
      echo $err->getMessage();
    }

    return $returnValue;
  }

  // Retorna un array de objetos de tipo Empleado, recuperados de la base de datos (con la descripción del perfil correspondiente y su foto).
  public static function TraerTodos(): array
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
            $table = "empleados";
            break;
          case 1:
            $table = "perfiles";
            break;
        }
        $pdoStatement_obj = $pdo->query("SELECT * FROM `$table`");
        $resultSet = $pdoStatement_obj->fetchAll(PDO::FETCH_ASSOC);
        array_push($array_resultSet, $resultSet);
      }

      $resultSet_empleados = $array_resultSet[0];
      $resultSet_perfiles = $array_resultSet[1];
      $array_empleados = array();

      foreach ($resultSet_empleados as $row) {
        $empleado = new Usuario();
        foreach ($row as $key => $value) {
          $empleado->$key = $value;
          if ($key === 'id_perfil') {
            foreach ($resultSet_perfiles as $perfil) {
              if ($perfil['id'] === $value) {
                $empleado->perfil = $perfil['descripcion'];
              }
            }
          }
        }
        array_push($array_empleados, $empleado);
      }

      $returnValue = $array_empleados;

    } catch (PDOException $err) {
      echo $err->getMessage();
    }

    return $returnValue;
  }
}