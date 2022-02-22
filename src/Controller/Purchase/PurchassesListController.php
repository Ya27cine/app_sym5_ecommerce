<?php 

namespace App\Controller\Purchase;

use App\Entity\User;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PurchassesListController extends AbstractController{


    protected $twig;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }


   /**
    * @Route("/commandes", name="purchase_index")
    */
    public function index() : Response{
        // 1. is auth ?
        /** @var User */
        $user = $this->getUser();
        if( ! $user ){
            return $this->redirectToRoute('homepage');
        }

        // 2. qui est connecte ?

        // 3. passe a twig 
        $html = $this->twig->render('purchase/index.html.twig',[
            'purchases' => $user->getPurchases()
        ]);

        return new Response($html);
    }
}
?>