<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBag;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CartController extends AbstractController
{
    /**
     * @Route("/cart/add/{id}", name="cart_add", requirements={"id" : "\d+"})
     */
    public function add($id, SessionInterface $session, ProductRepository $productRepository): Response
    {

     // 0. securisation : est-ce que le produit il existe :
     $product = $productRepository->find($id);

     if(! $product ){
        throw $this->createNotFoundException("Le produit $id n'existe pas !");
     }

      //dd($request->getSession());
      // 1. retrouver le panier, si n'existe pas alors prendre un tableau vide []
      $cart = $session->get('cart', []);

      // 2. voir si le produit $id existe deja :
      if(array_key_exists($id, $cart)){
        $cart[$id]++; // si c'est le cas, +1
      }else{
          $cart[$id] = 1; // sinon, ajouter le produit av la qnt 1
      }

      // enrigistrer le tableau mis a jour dans la session :
      $session->set('cart', $cart);


      /** @var FlashBag */
      $flahBag = $session->getBag('flashes');
      $flahBag->add('success',"Le produit a  bien ete ajoute au panier ");

      //dd($session);

      return $this->redirectToRoute('product_show',[
          'category_slug' => $product->getCategory()->getSlug(),
          'slug' => $product->getSlug()
      ]);
    }
}
