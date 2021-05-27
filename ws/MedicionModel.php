<?php

class MedicionModel
{
    private $PDOconn;
    
    public const TRAMOS=['tramo1', 'tramo2', 'tramo3', 'tramo4'];
    
    public function __construct()
    {
        $this->PDOconn=connect();
        if ($this->PDOconn)
            _log('Conectado a la base de datos.');        
        else
            _log('Error conectando a la base de datos.');        
    }
    
    public function getMedicion (int $day, int $month, int $year, string $estacion) : array
    {        
        $tramos=['tramo1'=>-1,'tramo2'=>-1,'tramo3'=>-1,'tramo4'=>-1];        
        $tramosdberr=['tramo1'=>-2,'tramo2'=>-2,'tramo3'=>-2,'tramo4'=>-2];        
        
        //Filtramos los datos
        $estacion=filter_var($estacion,FILTER_SANITIZE_STRING);
        $day=is_int($day)?$day:false;
        $month=is_int($month)?$month:false;
        $year=is_int($year)?$year:false;
                
        //Encapsulamos los datos de la fecha en un objeto DateTime                
        $date=false;
        $now=false;
        if ($day!==false && $month!==false && $year!==false)
        {
            $date=(new DateTime())->setDate($year, $month, $day);        
            $now=new DateTime();
        }
        
        
        //Verificamos que la fecha es correcta, que no es futura, y el texto $estacion
        
        if (!$date || $date>$now || !$estacion || strlen($estacion)<1)
        {
            /*Nota: en el if anterior realmente es redundante evaluar strlen($estacion)<1 porque 
                * !$estacion será true cuando la cadena es vacía.
                */            
            _log("GetMedicion invocado con datos incorrectos: $day, $month, $year, $estacion");            
        }                        
        else if (!$this->PDOconn)
        {
            $tramos=$tramosdberr;
        }
        else {
            $query='SELECT tramo, recuento from medicion where fecha=:fecha and estacion=:estacion';
            $sta=$this->PDOconn->prepare($query);
            if ($sta->execute(['fecha'=>$date->format('Y-m-d'),'estacion'=>$estacion]))
            {
                while ($res=$sta->fetch(PDO::FETCH_ASSOC))
                {
                    $tramos[$res['tramo']]=$res['recuento'];
                }
            }
        }        
        return $tramos;
    }
    
    public function nuevaMedicion ($medicion) : int
    {        
        $ret=-1;
        
        //Filtramos los datos        
        $estacion=filter_var($medicion->estacion,FILTER_SANITIZE_STRING);
        $day=is_int($medicion->day)?$medicion->day:false;
        $month=is_int($medicion->month)?$medicion->month:false;
        $year=is_int($medicion->year)?$medicion->year:false;
        $tramo=filter_var($medicion->tramo,FILTER_SANITIZE_STRING);
        $recuento=is_int($medicion->recuento)?$medicion->recuento:false;
        
        //Encapsulamos la fecha en un objeto DateTime
        $date=false;
        $now=false;
        if ($day!==false && $month!==false && $year!==false)
        {
            $date=(new DateTime())->setDate($year, $month, $day);        
            $now=new DateTime();            
        }
        
        if ($date && $date<=$now  //Verificamos la fecha.
            && $estacion!==false && $estacion!==null && strlen($estacion)>0 //Verificamos la estación
            && in_array($tramo,self::TRAMOS) //Verificamos el tramo
            && $recuento!==false && $recuento<0 //Verificamos el recuento
           )
        {            
            $ret=-2;
            if ($this->PDOconn)
            {
                $query='INSERT INTO medicion (fecha, estacion, tramo, recuento)'
                        . ' VALUES (:fecha,:estacion,:tramo,:recuento)'
                        . ' ON DUPLICATE KEY UPDATE recuento=:recuento';
                $data=['fecha'=>$date->format('Y-m-d'),
                       'estacion'=>$estacion,
                       'tramo'=>$tramo,
                       'recuento'=>$recuento];
                $sta=$this->PDOconn->prepare($query);
                if ($sta->execute($data))
                {
                    $ret=$sta->rowCount()==1?0:1;
                    _log("NuevaMedicion con resultado $ret.");                        
                }
                
            }
            else {
                _log("NuevaMedicion invocado con datos incorrectos");                        
            }
        }             
        else {
            _log("NuevaMedicion invocado con datos incorrectos");                        
        }
        
        return $ret;
    }
}

