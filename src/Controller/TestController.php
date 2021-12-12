<?php 
namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class TestController 
{
  /**
   * @Route("/", name="index")
   */
    public function index()
    {
       
       dump("ca fonctionne ");
       die();
    }

    /**
     * @Route("/test/{age<\d+>?-3}", name="test", methods={"GET", "POST"},
     *  host="127.0.0.1", schemes={"http", "https"})
    */
    public function test($age, Request $request){
       // $age = $_GET['age'];
        //$age = $request->query->get('age', 0);
        //$age = $request->attributes->get('age', 0);

     return new Response("Vous avez $age ans !");
    }
}