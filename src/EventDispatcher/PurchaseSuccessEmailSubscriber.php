<?php  

namespace App\EventDispatcher;

use App\Event\PurchaseSuccessEvent;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Security\Core\Security;
use App\Entity\User;
use App\Entity\Purchase;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mime\Address;

class PurchaseSuccessEmailSubscriber  implements EventSubscriberInterface
{

    protected $logger;

    protected $mailer;
    protected $security;

    public function __construct(LoggerInterface $logger, MailerInterface $mailer, Security $security)
    {
        $this->logger = $logger;
        $this->mailer = $mailer;
        $this->security = $security;
    }
    public static function getSubscribedEvents()
    {
        return [
            'purchase.success' => 'sendSuccessEmail',
        ];
    }

    public function sendSuccessEmail(PurchaseSuccessEvent $purchaseSuccessEvent){
       // 1 recupere user actuelle
       /** @var User */
       $user = $this->security->getUser();

       // 2 recuperer la commande 
       /** @var Purchase */
       $purchase = $purchaseSuccessEvent->getPurchase();

      // dd($purchase);
       // 3 ecrire le mail :
       $email = new TemplatedEmail();
       $email->to( new Address($user->getEmail(), $user->getFullname()))
            ->from("contact@prostam.com")
           ->subject("Bravo, Votre commande (  ".$purchase->getId()." ) a bien ete confirmee ")
           ->htmlTemplate('emails/purchase_success.html.twig')
           ->context([
               'purchase' => $purchase,
               'user' => $user
           ]);

           $this->mailer->send( $email );

       

       $this->logger->info("Email envoye  pour la commande n: ".$purchase->getId() );
     }


}
?>