<?php  

namespace App\Controller\Purchase;

use App\Entity\Purchase;
use App\Event\PurchaseSuccessEvent;
use App\Service\CartService;
use App\Repository\PurchaseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class PurchasePaymentSuccessController extends AbstractController{

    /**
     * @Route("/purchase/terminate/{id}", name="purchase_payement_success")
     * @IsGranted("ROLE_USER")
     */
    public function success($id, PurchaseRepository $purchaseRepository, EntityManagerInterface $em,
    CartService $cartService, EventDispatcherInterface $dispatcher): Response
    {

        // je recupere la commande:
        $purchase  = $purchaseRepository->find($id);

        if(
            ! $purchase ||
            ( $purchase && $purchase->getClient() !== $this->getUser()) || 
            ( $purchase && $purchase->getStatus() === Purchase::STATUS_PAID)
        )
            {
                $this->addFlash('warning',"La commande n existe pas");
                return $this->redirectToRoute("purchase_index");
            }
        
        // je la fait passer au status payee
        $purchase->setStatus( Purchase::STATUS_PAID);
        $em->flush();

        // je vide l panier
        $cartService->empty();

        // lancer un event :
        $dispatcher->dispatch( new PurchaseSuccessEvent($purchase),
                                'purchase.success');

        // je redirige av un flash vers la liste des commandes
        $this->addFlash('success',"La commande a ete paye et confirmee !");

        return $this->redirectToRoute('purchase_index');
    }
}


?>