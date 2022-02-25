<?php  

namespace App\Controller\Purchase;

use App\Form\CartConfirmationType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Security;

class PurchaseConfirmationController {

    protected $formFactory;
    protected $security;
    protected $route;

    public function __construct(FormFactoryInterface $formFactory, Security $security,
    RouterInterface $route)
    {
        $this->formFactory = $formFactory;
        $this->security = $security;
        $this->route = $route;
    }

    /**
     * @Route("/purchase/confirm", name="purchase_confirm")
     */
    public function confirm(Request $request, FlashBagInterface $flashBag){


        $form = $this->formFactory->create(CartConfirmationType::class);

        $form->handleRequest($request);

        
        // si le form n est pas  soumis
        if ( ! $form->isSubmitted()) { 
            $flashBag->add('warning', 'Vous devez remplir le formulaire de confirmation');
            return new RedirectResponse(
                    $this->route->generate('cart_show')
            );
        }

        $user = $this->security->getUser();
        if(! $user ){
            $flashBag->add('warning', 'Vous devez etre connecte pour confirmer une commande');

        }

       
        
    }



}


?>