<?php
namespace App\Controller;

use App\Taxes\Caculator;
use Cocur\Slugify\Slugify;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Psr\Log\LoggerInterface;
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
     * @Route("/hello/{name?World}", name="say_hello")
     */
    public function index($name, Caculator $caculator, Slugify $sluger, Environment $twig): Response
    {
        dump($twig);
        dump( $sluger->slugify("Hello World ! "));


        $this->logger->error('Mon msg de log !');
        $tva  = $caculator->calcul(100);
        dump($tva);
       return new Response("Hello $name");
    }
}