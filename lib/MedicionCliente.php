<?php


/** Clase desde el "scope" del cliente del servicio web. **/

class MedicionCliente {
            
    private $day;
    private $month;
    private $year;
    private $tramo;
    private $estacion;
    private $recuento;
    
    public function setDay($day): void {
        $this->day = $day;
    }

    public function setMonth($month): void {
        $this->month = $month;
    }

    public function setYear($year): void {
        $this->year = $year;
    }

    public function setTramo($tramo): void {
        $this->tramo = $tramo;
    }

    public function setEstacion($estacion): void {
        $this->estacion = $estacion;
    }

    public function setRecuento($recuento): void {
        $this->recuento = $recuento;
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

    public function getTramo() {
        return $this->tramo;
    }

    public function getEstacion() {
        return $this->estacion;
    }

    public function getRecuento() {
        return $this->recuento;
    }

}