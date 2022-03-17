<?php  

namespace App\EventDispatcher;

use App\Event\ConstantEvent;
use App\Event\ProductViewEvent;
use Symfony\Bridge\Monolog\Logger;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Psr\Log\LoggerInterface;

class ProductViewSubscriber implements EventSubscriberInterface {

    protected $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }
    public static function getSubscribedEvents()
    {
        return [
            ConstantEvent::PRODUCT_VIEW => 'sendEmail',
           
        ];
    }


    public function sendEmail(ProductViewEvent $event){

        $this->product = $event->getProduct();

        $this->logger->info("Email envoye a l admin pour le produit  ".$this->product->getId());

    }

}


?>