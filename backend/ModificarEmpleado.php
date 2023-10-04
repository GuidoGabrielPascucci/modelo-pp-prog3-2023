<?php

// ModificarEmpleado.php: Se recibirán por POST los siguientes valores: empleado_json (id, nombre, correo, clave, id_perfil, sueldo y pathFoto, en formato de cadena JSON) y foto (para modificar un empleado en la base de datos. Invocar al método Modificar.
// Nota: El valor del id, será el id del empleado 'original', mientras que el resto de los valores serán los del empleado modificado.
// Se retornará un JSON que contendrá: éxito(bool) y mensaje(string) indicando lo acontecido.