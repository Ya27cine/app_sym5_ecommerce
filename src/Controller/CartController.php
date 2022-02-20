<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    /**
     * @Route("/cart/add/{id}", name="cart_add", requirements={"id" : "\d+"})
     */
    public function add($id, Request $request, ProductRepository $productRepository): Response
    {

        // 0. securisation : est-ce que le produit il existe :
        $product = $productRepository->find($id);

        if(! $product ){
            throw $this->createNotFoundException("Le produit $id n'existe pas !");
        }

      //dd($request->getSession());
      // 1. retrouver le panier, si n'existe pas alors prendre un tableau vide []
      $cart = $request->getSession()->get('cart', []);

      // 2. voir si le produit $id existe deja :
      if(array_key_exists($id, $cart)){
        $cart[$id]++; // si c'est le cas, +1
      }else{
          $cart[$id] = 1; // sinon, ajouter le produit av la qnt 1
      }

      // enrigistrer le tableau mis a jour dans la session :
      $request->getSession()->set('cart', $cart);

      return $this->redirectToRoute('product_show',[
          'category_slug' => $product->getCategory()->getSlug(),
          'slug' => $product->getSlug()
      ]);
    }
}
