<?php  

namespace App\EventDispatcher;

use App\Event\PurchaseSuccessEvent;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
class PurchaseSuccessEmailSubscriber  implements EventSubscriberInterface
{

    protected $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }
    public static function getSubscribedEvents()
    {
        return [
            'purchase.success' => 'sendSuccessEmail',
        ];
    }

    public function sendSuccessEmail(PurchaseSuccessEvent $purchaseSuccessEvent){
        $purchase = $purchaseSuccessEvent->getPurchase();
       // dd($purchase);

       $this->logger->info("Email envoye  pour la commande n: ".$purchase->getId() );
     }


}


?>