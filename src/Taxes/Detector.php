<?php 

namespace App\Taxes;

class Detector 
{
    protected $seuil;

    public function __construct($seuil)
    {
        $this->seuil = $seuil;
    } 

    public function detector(float $prix) : bool
    {
       return $prix >=  $this->seuil;
    }
}