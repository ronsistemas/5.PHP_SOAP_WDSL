<?php

define('APP_ROOT_DIR', __DIR__);

define('WDSL_PATH', 'http://localhost/dwes05_solucion/ws/tarea05_solucion.wsdl');


require_once 'config/settings.php';
require_once 'Smarty.class.php';
require_once 'lib/Peticion.php';
require_once 'lib/MedicionCliente.php';

session_start();

/* Carga de smarty */
$smarty = new Smarty();

$smarty->template_dir = TEMPLATE_DIR;
$smarty->compile_dir = TEMPLATE_C_DIR;
$smarty->config_dir = CONFIG_DIR;
$smarty->cache_dir = CACHE_DIR;

$lastOpResult=[];
$p=new Peticion();

$medicion=null;

if ($p->has('btn','day','month','year','estacion'))
{
    try {
        $medicion=new MedicionCliente();
        $medicion->setDay($p->getInt('day'));
        $medicion->setMonth($p->getInt('month'));
        $medicion->setYear($p->getInt('year'));
        $medicion->setEstacion($p->getString('estacion'));   
        $_SESSION['info']=serialize($medicion);
    } catch (Exception $ex) {
        $lastOpResult[]='[ERROR]'.$ex->getMessage();
        $medicion=null;
    }
}
else if (isset($_SESSION['info']))
{
    $medicion=unserialize($_SESSION['info']);
}

$result=null;

if ($medicion instanceof MedicionCliente)
{
    $smarty->assign('day',$medicion->getDay());
    $smarty->assign('month',$medicion->getMonth());
    $smarty->assign('year',$medicion->getYear());
    $smarty->assign('estacion',$medicion->getEstacion());
    $medicionSC = new SoapClient(WDSL_PATH,array('trace' => 1));        
    
    try {                     
        $result=$medicionSC->getMedicion($medicion->getDay(), $medicion->getMonth(), 
                $medicion->getYear(), $medicion->getEstacion());    
        $smarty->assign('result',$result);
    }
    catch (SoapFault $ex)
    {
            $lastOpResult[]="[ERROR] Error al conectar al servicio web."; 
            echo $medicionSC->__getLastRequest();
            echo $medicionSC->__getLastResponse();
    }            
} 
    
$smarty->assign('lastOpResult', $lastOpResult);

$smarty->display('ejercicio5.tpl');
