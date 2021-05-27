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
    
$showForm=true;



if ($p->has('btn_new'))
{

    $medicion=new MedicionCliente();
    $dateParts=[];
    
    if (!$p->has('fecha'))
    {
        $lastOpResult[]='No se ha proporcionado la fecha.';
    } 
    else if (preg_match('/^(\d+)\/(\d+)\/(\d+)$/', $p->getString('fecha'), $dateParts))
    {
        $medicion->setDay($dateParts[1]);
        $medicion->setMonth($dateParts[2]);
        $medicion->setYear($dateParts[3]);
        $smarty->assign('fecha',$p->getString('fecha'));
    } 
    else
    {
        $lastOpResult[]='La fecha proporcionada no es válida.';
    }
    
    if (!$p->has('tramo'))
    {
        $lastOpResult[]='No se ha proporcionado el tramo.';
    }
    else
    {
        $medicion->setTramo($p->getString('tramo'));
        $smarty->assign('tramo',$medicion->getTramo());

    }
    
    if (!$p->has('estacion'))
    {
        $lastOpResult[]='No se ha proporcionado la estación.';
    }
    else
    {
        $medicion->setEstacion($p->getString('estacion'));
        $smarty->assign('estacion',$medicion->getEstacion());
    }
    
    if (!$p->has('recuento') or !is_numeric($_POST['recuento']))
    {
        $lastOpResult[]='No se ha proporcionado el recuento o no es un número.';
    }
    else
    {
        $medicion->setRecuento($p->getString('recuento'));
        $smarty->assign('recuento',$medicion->getRecuento());
    }
        
    if (empty($lastOpResult))    
    {        
        $lastOpResult[]='Procediendo a realizar la petición SOAP.';
        try {
            $medicionSC = new SoapClient(WDSL_PATH,array('trace' => 1));        
            switch ($medicionSC->nuevaMedicion($medicion)) {
                case -1:
                    $lastOpResult[] = "Existe un error en algún dato y no se puede procesar.";
                    break;
                case -2:
                    $lastOpResult[] = "Existe un error en la base de datos y no se puede almacenar.";
                    break;
                case 0:
                    $lastOpResult[] = "Operación efectuada correctamente (nuevo registro).";
                    $_SESSION['info'] = serialize($medicion);
                    $smarty->assign('medicion', $medicion);
                    break;
                case 1:
                    $lastOpResult[] = "Operación efectuada correctamente (registro modificado).";
                    $_SESSION['info'] = serialize($medicion);
                    $smarty->assign('medicion', $medicion);
                    break;
                default:
                    $lastOpResult[] = "Resultado de la operación desconocido.";
                    break;
            }
        }
        catch (SoapFault $ex)
        {
            $lastOpResult[]="Error al conectar al servicio web.";
        }        
    }
}

$smarty->assign('lastOpResult', $lastOpResult);

$smarty->display('ejercicio4.tpl');
