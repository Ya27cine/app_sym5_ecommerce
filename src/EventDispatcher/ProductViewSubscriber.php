<?php  

namespace App\EventDispatcher;

use App\Event\ConstantEvent;
use App\Event\ProductViewEvent;
use Symfony\Bridge\Monolog\Logger;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;

class ProductViewSubscriber implements EventSubscriberInterface {

    protected $logger;
    protected $mailer;

    public function __construct(LoggerInterface $logger, MailerInterface $mailer)
    {
        $this->logger = $logger;
        $this->mailer = $mailer;
    }
    public static function getSubscribedEvents()
    {
        return [
            ConstantEvent::PRODUCT_VIEW => 'sendEmail',
           
        ];
    }


    public function sendEmail(ProductViewEvent $event){

        $this->product = $event->getProduct();


    //     $email = new TemplatedEmail();
    //     $email->from( new Address("contact@prostam.fr", "Infos de la boutique") )
    //     ->to("admin@prostam.fr")
    //     ->subject("Visite du produit n:".$this->product->getId())
    //     ->htmlTemplate("emails/product_view.html.twig")
    //     ->context([
    //             'product' => $this->product
    //     ]);
        

    //    $this->mailer->send($email);

        $this->logger->info("Email envoye a l admin pour le produit  ".$this->product->getId());
    }

}


?>