<?php
namespace App\Form\DataTransformer;


use Symfony\Component\Form\DataTransformerInterface;


class CentimeTransformer implements DataTransformerInterface{
    
	function transform($value) {

        if(null === $value){
            return;
        }

        return $value / 100;
	}
	
	function reverseTransform($value) {

        if(null === $value){
            return;
        }

        return $value * 100;
	}
}

?>