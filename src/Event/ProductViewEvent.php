<?php 

namespace App\Event;

use App\Entity\Product;
use Symfony\Contracts\EventDispatcher\Event;

class ProductViewEvent extends Event{
    private $protuct;

    public function __construct(Product $p)
    {
        $this->protuct = $p;
    }

    public function getProduct() : Product {
        return $this->protuct;
    }
}
?>