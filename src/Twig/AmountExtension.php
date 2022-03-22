<?php 

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class AmountExtension extends AbstractExtension {


            public function getFilters()
            {
                return [
                    new TwigFilter('amount', function($value){
                               return  number_format( $value / 100,  2, ',', ' ')  . " €";
                    })
                ];
            }
}
?>