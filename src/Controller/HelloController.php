<?php
namespace App\Controller;

use App\Taxes\Caculator;
use App\Taxes\Detector;
use Cocur\Slugify\Slugify;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Twig\Environment;

class HelloController extends AbstractController
{
    protected $logger;
    protected $caculator;
    public function __construct(LoggerInterface $loggerInterface, Caculator $caculator)
    {
        $this->logger = $loggerInterface;
        $this->caculator = $caculator;
    }


    /**
     * @Route("/h/{name?World}", name="say_home")
     */
    public function home($name, Environment $twig): Response
    {
        $html  = $twig->render("hello.html.twig", [
            'prenom' => $name,
            "formateur1" => [
               "nom" => "Khelifa",
               "prenom" => "Yassine"
            ],
            "formateur2" => [
                "nom" => "Hamou-Maamar",
                "prenom" => "Sabah"
             ]
        ]);
       return new Response($html);
    }





    /**
     * @Route("/hello/{name?World}", name="say_hello")
     */
    public function index(Request $request ,$name, Caculator $caculator, Slugify $sluger, Environment $twig,
    Detector $detector): Response
    {
        dump( $detector->detector(110) );
        dump( $detector->detector(10) );

        dump( $sluger->slugify("Hello World ! "));


        $this->logger->error('Mon msg de log !');
        $tva  = $caculator->calcul(100);
        dump($tva);
       return new Response("Hello $name");
    }
}