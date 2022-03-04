<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE');
header('Allow: GET, POST, OPTIONS, PUT, DELETE');

/* Variables de entorno */
global $wpdb;
// $table = $wpdb->polar_usuarios;
$table = 'wp_polar_usuarios'; // TODO: Remove this line
$columns = [
  'rubro_empresa',
  'codigo_CEP',
  'nombre_empresa',
  'estatus_negocio',
  'RIF',
  'nombre_completo',
  'nombre_apellido',
  'telefono_1',
  'telefono_2',
  'correo_electronico',
  'direccion_fisica',
  'direccion_fiscal',
  'estado',
  'ciudad',
  'municipio',
  'parroquia',
  'punto_de_referencia',
  'urbanizacion'
];
$resCSV = array(); // Variable para las respuestas del archivo subido
$registros = ''; // Variable para los registros del INSERT
$registrosInsertados = 0; // Variable de conteo de INSERT's
$registrosActualizados = 0; // Variables de conteo de UPDATE's

/* Solo recibe metodo POST */
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  $resCSV['error_1'] = 'Método no permitido';
  echo json_encode($resCSV);
  return;
}

/* Control para tipos de archivos permitidos */
$mimes = array(
  'text/csv',
  'application/csv',
  'application/vnd.ms-excel',
  'application/vnd.msexcel',
);
if (in_array($_FILES['file']['type'], $mimes)) {
  $resCSV['archivo_aceptado'] = true;
} else {
  $resCSV['archivo_aceptado'] = false;
  $resCSV['error_2'] = "Lo siento, el achivo del tipo ({$_FILES['file']['type']}) no esta permitido";
  echo json_encode($resCSV);
  return;
}

/* Sube el archivo CSV solo si tiene datos */
if ($_FILES['file']['size'] > 0) {

  /* Creamos la tabla */
  $query = "CREATE TABLE IF NOT EXISTS $table (
    `rubro_empresa` VARCHAR(255) NULL DEFAULT NULL COLLATE 'utf8_general_ci',
    `codigo_CEP` BIGINT(20) NULL DEFAULT NULL,
    `nombre_empresa` VARCHAR(255) NULL DEFAULT NULL COLLATE 'utf8_general_ci',
    `estatus_negocio` VARCHAR(255) NULL DEFAULT NULL COLLATE 'utf8_general_ci',
    `RIF` VARCHAR(255) NULL DEFAULT NULL COLLATE 'utf8_general_ci',
    `nombre_completo` VARCHAR(255) NULL DEFAULT NULL COLLATE 'utf8_general_ci',
    `nombre_apellido` VARCHAR(255) NULL DEFAULT NULL COLLATE 'utf8_general_ci',
    `telefono_1` VARCHAR(255) NULL DEFAULT NULL COLLATE 'utf8_general_ci',
    `telefono_2` VARCHAR(255) NULL DEFAULT NULL COLLATE 'utf8_general_ci',
    `correo_electronico` VARCHAR(255) NULL DEFAULT NULL COLLATE 'utf8_general_ci',
    `direccion_fisica` VARCHAR(255) NULL DEFAULT NULL COLLATE 'utf8_general_ci',
    `direccion_fiscal` VARCHAR(255) NULL DEFAULT NULL COLLATE 'utf8_general_ci',
    `estado` VARCHAR(255) NULL DEFAULT NULL COLLATE 'utf8_general_ci',
    `ciudad` VARCHAR(255) NULL DEFAULT NULL COLLATE 'utf8_general_ci',
    `municipio` VARCHAR(255) NULL DEFAULT NULL COLLATE 'utf8_general_ci',
    `parroquia` VARCHAR(255) NULL DEFAULT NULL COLLATE 'utf8_general_ci',
    `punto_de_referencia` VARCHAR(255) NULL DEFAULT NULL COLLATE 'utf8_general_ci',
    `urbanizacion` VARCHAR(255) NULL DEFAULT NULL COLLATE 'utf8_general_ci'
  )
  COLLATE='utf8_general_ci'
  ENGINE=InnoDB
  ;";
  // $res = $wpdb->query( $wpdb->prepare ( $query ) );
  // $resCSV["query_CREATE"] = $query;

  /* Obtenemos el archivo para leer */
  $csv = $_FILES['file']['tmp_name'];
  $fileCSV = fopen($csv, 'r');

  /* Obtenemos las filas de los registros */
  while (($fileData = fgetcsv($fileCSV, 0, ';')) == true) {

    /* Modificaciones y comrobaciones previas en el arreglo */
    $userData = array();
    for ($i=0; $i < count($fileData); $i++) {

      /* Verificar si el dato tiene una estructura similar */
      if (count($fileData) !== count($columns)) {
        
        $resCSV['error_3'] = 'Hay algun archivo con la estructura incorrecta';
        continue;
      }

      $resCSV['error_3'] = false;

      /* Verifica si algun dato presenta false */
      if (!json_encode($fileData[$i])) {
        $fileData[$i] = '';
      }

      /* Acomodamos el array de forma descriptiva */
      $userData[$columns[$i]] = trim($fileData[$i]);

    }

    if ($resCSV['error_3']) {
      continue;
    }

    /* Verificacion si el registo existe en DB */
    $where = "WHERE codigo_CEP = {$userData['codigo_CEP']}";
    $query = "SELECT * FROM $table $where;";
    // $res = $wpdb->query( $wpdb->prepare ( $query ) );
    // $res = $wpdb->get_row( $wpdb->prepare ( $query ) );
    // $res = $wpdb->get_results( $wpdb->prepare ( $query ) );
    // $resCSV["query_SELECT"] = $query;

    // if ($res) {
    if (false) { // TODO: Remove this line
      continue;

      /* Hace un UPDATE de los registros */
      // $query = "UPDATE $table SET";
      // $query .= " rubro_empresa =       \"{$userData['rubro_empresa']}\",";
      // $query .= " codigo_CEP =          \"{$userData['codigo_CEP']}\",";
      // $query .= " nombre_empresa =      \"{$userData['nombre_empresa']}\",";
      // $query .= " estatus_negocio =     \"{$userData['estatus_negocio']}\",";
      // $query .= " RIF =                 \"{$userData['RIF']}\",";
      // $query .= " nombre_completo =     \"{$userData['nombre_completo']}\",";
      // $query .= " nombre_apellido =     \"{$userData['nombre_apellido']}\",";
      // $query .= " telefono_1 =          \"{$userData['telefono_1']}\",";
      // $query .= " telefono_2 =          \"{$userData['telefono_2']}\",";
      // $query .= " correo_electronico =  \"{$userData['correo_electronico']}\",";
      // $query .= " direccion_fisica =    \"{$userData['direccion_fisica']}\",";
      // $query .= " direccion_fiscal =    \"{$userData['direccion_fiscal']}\",";
      // $query .= " estado =              \"{$userData['estado']}\",";
      // $query .= " ciudad =              \"{$userData['ciudad']}\",";
      // $query .= " municipio =           \"{$userData['municipio']}\",";
      // $query .= " parroquia =           \"{$userData['parroquia']}\",";
      // $query .= " punto_de_referencia = \"{$userData['punto_de_referencia']}\",";
      // $query .= " urbanizacion =        \"{$userData['urbanizacion']}\" ";
      // $query .= " $where";
      // $res = $wpdb->query( $wpdb->prepare ( $query ) );
      // $resCSV["query_UPDATE"] = $query;
      // $registrosActualizados ++;

    } else {

      /* Pepara los datos para el INSERT de los nuevos registros */
      $registros .= "(";
      $registros .= " \"{$userData['rubro_empresa']}\",";
      $registros .= " \"{$userData['codigo_CEP']}\",";
      $registros .= " \"{$userData['nombre_empresa']}\",";
      $registros .= " \"{$userData['estatus_negocio']}\",";
      $registros .= " \"{$userData['RIF']}\",";
      $registros .= " \"{$userData['nombre_completo']}\",";
      $registros .= " \"{$userData['nombre_apellido']}\",";
      $registros .= " \"{$userData['telefono_1']}\",";
      $registros .= " \"{$userData['telefono_2']}\",";
      $registros .= " \"{$userData['correo_electronico']}\",";
      $registros .= " \"{$userData['direccion_fisica']}\",";
      $registros .= " \"{$userData['direccion_fiscal']}\",";
      $registros .= " \"{$userData['estado']}\",";
      $registros .= " \"{$userData['ciudad']}\",";
      $registros .= " \"{$userData['municipio']}\",";
      $registros .= " \"{$userData['parroquia']}\",";
      $registros .= " \"{$userData['punto_de_referencia']}\",";
      $registros .= " \"{$userData['urbanizacion']}\" ";
      $registros .= "),";
      $registrosInsertados ++;
    
    }

  }

  /* Hace un INSERT de los nuevos registros */
  // if ($registros) {
  if (true) { // TODO: Remove this line
    $registros = trim($registros, ',');
    $query = "INSERT INTO $table (";
    foreach ($columns as $value) {
      $query .= " $value,";
    }
    $query = trim($query, ',');
    $query .= " ) VALUES $registros;";
    // $res = $wpdb->query( $wpdb->prepare ( $query ) );
    // $resCSV["query_INSERT"] = $query;
  }

  fclose($fileCSV);

  $resCSV['archivo_subido'] = true;
  $resCSV['registrosInsertados'] = $registrosInsertados;
  $resCSV['registrosActualizados'] = $registrosActualizados;
  echo json_encode($resCSV);
  return;

} else {

  $resCSV['archivo_subido'] = false;
  $resCSV['error_4'] = 'El archivo no posee datos';
  echo json_encode($resCSV);
  return;

}