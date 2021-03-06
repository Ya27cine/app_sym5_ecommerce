<?php 

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Address;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     */
    public function homepage(ProductRepository $productRepository)
    {

        $products = $productRepository->findBy([], [], 4);
       // dd( $products );

       return $this->render('home.html.twig',[
           'products' => $products
       ]);
    }

     /**
     * @Route("/testp", name="testpp")
     */
    public function testcreateproduct(EntityManagerInterface $em)
    {
        $product = new Product();
                $product->setName("tTiter #jgf jf 764")
                ->setPrice( 2400 )
                ->setMainPicture( "htttps://www.google.com")
                ->setShortDescriotion( "Lorem ldkfjf dhjffnf jfh");

            $em->persist($product);
            $em->flush();

                dd($product);
    }

    /**
     * @Route("test-email", name="emailsend")
     */
    public function sendEmailTest(MailerInterface $mailer){
        $email = new Email();
        $email->to( new Address("yey@jfkf.fr", "Admin"))
             ->from("contact@prostam.com")
            ->subject("Bravo, Votre command bien ete confirmee ")
            ->text("test");
 
            $mailer->send( $email );
            return new Response("Sent !!");
    }



}