<?php

/** Clase desde el "scope" de la implementacion del servicio web. **/


class MedicionWS {
            
    
    public const TRAMOS=['tramo1','tramo2','tramo3','tramo4'];
    
    private $day;
    private $month;
    private $year;
    private $tramo;
    private $estacion;
    private $recuento;
    
    public function __construct($date,$tramo,$estacion,$recuento)
    {
        $date=trim($date);
        $dateT=DateTime::createFromFormat('d/m/Y', $date);
        
        if ($dateT)
        {
            $this->day=intval($dateT->format('d'));
            $this->month=intval($dateT->format('m'));
            $this->year=intval($dateT->format('Y'));
        }else {
            throw new InvalidArgumentException("La fecha no es correcta.");
        }
        
        
        $tramo= strtolower(trim($tramo));
        if (in_array($tramo, MedicionWS::TRAMOS)) {
            $this->tramo=$tramo;
        }
        else {
            throw new InvalidArgumentException("El tramo no es correcto.");
        }
        
        $estacion= strtolower(trim($estacion));
        if (strlen($estacion)>0){
            $this->estacion=$estacion;
        }
        else
        {
            throw new InvalidArgumentException("No se ha indicado la estación.");        
        }
                
        if (is_numeric($recuento) && $recuento=intval($recuento)>=0){
            $this->recuento=$recuento;
        }
        else
        {
            throw new InvalidArgumentException("El recuento no es válido.");        
        }
    }
    
    public function getDay() {
        return $this->day;
    }

    public function getMonth() {
        return $this->month;
    }

    public function getYear() {
        return $this->year;
    }

    public function getDateDBFormat() {
        return $this->year.'-'.$this->month.'-'.$this->day;
    }
    
    public function getTramo() {
        return $this->tramo;
    }

    public function getEstacion() {
        return $this->estacion;
    }

    public function getRecuento() {
        return $this->recuento;
    }

    public function getDataAsArrayForDB ()
    {
        return ['fecha'=>$this->getDateDBFormat(),
                'tramo'=>$this->tramo,
                'estacion'=>$this->estacion,
                'recuento'=>$this->recuento];
    }

    
}
