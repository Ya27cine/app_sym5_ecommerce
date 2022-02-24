<?php 

namespace App\Controller\Purchase;

use App\Entity\User;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Finder\Exception\AccessDeniedException;

class PurchassesListController extends AbstractController{



   /**
    * @Route("/commandes", name="purchase_index")
    */
    public function index() : Response{
        // 1. is auth ?
        /** @var User */
        $user = $this->getUser();
        if( ! $user ){
           throw new AccessDeniedException("Vous devez etre connecte pour acceder a vos commandes");
        }

        // 2. qui est connecte ?

        // 3. passe a twig 
        return $this->render('purchase/index.html.twig',[
            'purchases' => $user->getPurchases()
        ]);
    }
}
?>