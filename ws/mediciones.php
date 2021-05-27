<?php

/* Volcamos los errores php a un archivo, de esta forma, podremos
analizar que errores se han producido.
Usa el script "verlog.php" para ver el log.
*/
ini_set("log_errors", 1);
ini_set("error_log", "ws-errors.log");
ini_set("display_errors", 0);
error_reporting(E_ALL);

require_once 'lib/logger.php';
require_once 'lib/conn.php';
require_once 'config/settings.php';

$baseURL= strtolower(dirname($_SERVER['REQUEST_URI']));

require_once 'MedicionWS.php';
require_once 'MedicionModel.php';

$uri="http://localhost/$baseURL";
$server = new SoapServer("$uri/tarea05_solucion.wsdl");

$server->setClass('MedicionModel');
$server->handle();


