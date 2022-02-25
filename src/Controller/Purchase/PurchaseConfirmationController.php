<?php  

namespace App\Controller\Purchase;

use DateTime;
use App\Entity\Purchase;
use App\Entity\PurchaseItem;
use App\Service\CartService;
use App\Form\CartConfirmationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PurchaseConfirmationController extends AbstractController {

    protected $formFactory;
    protected $security;
    protected $route;
    protected $cartService;
    protected $em;

    public function __construct(FormFactoryInterface $formFactory, Security $security,
    RouterInterface $route, CartService $cartService, EntityManagerInterface $em)
    {
        $this->formFactory = $formFactory;
        $this->security = $security;
        $this->route = $route;
        $this->cartService = $cartService;
        $this->em = $em;
    }

    /**
     * @Route("/purchase/confirm", name="purchase_confirm")
     * @IsGranted("ROLE_USER", message="Vous devez etre connecte pour confirmer une commande")
     */
    public function confirm(Request $request){


        $form = $this->formFactory->create(CartConfirmationType::class);

        $form->handleRequest($request);

        
        // si le form n est pas  soumis
        if ( ! $form->isSubmitted()) { 
            $this->addFlash('warning', 'Vous devez remplir le formulaire de confirmation');
            return new RedirectResponse(
                    $this->route->generate('cart_show')
            );
        }

        $user = $this->security->getUser();
       

        $cartItems = $this->cartService->getDetailCartItems();

        if( count($cartItems) === 0){
            $this->addFlash('warning', 'Vous devez remplir le formulaire de confirmation');
            return new RedirectResponse(
                    $this->route->generate('cart_show')
            );
        }

            /**
             * @var Purchase
             */
            $purchase = $form->getData();

            $purchase->setClient( $user )
                  ->setPurchasedAt(new DateTime());
            
            $this->em->persist($purchase);
            
            $total = 0;
            foreach($cartItems as $cartItem){
                $purchaseItem = new PurchaseItem;
                $purchaseItem->setPurchase($purchase)
                ->setProduct($cartItem->product)
                ->setProductName($cartItem->product->getName())
                ->setProductPrice($cartItem->product->getPrice())
                ->setQuantity($cartItem->qty)
                ->setTotal($cartItem->getTotal());

                $total += $cartItem->getTotal();

                $this->em->persist($purchaseItem);
            }
            $purchase->setTotal($total);        

            $this->em->flush();

            $this->cartService->empty();

            $this->addFlash('success', 'La commandea bien ete enregistree');

            return new RedirectResponse( $this->route->generate('purchase_index'));

        
    }



}


?>