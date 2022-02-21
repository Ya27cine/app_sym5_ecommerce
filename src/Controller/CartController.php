<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use App\Service\CartService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBag;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;

class CartController extends AbstractController
{
    /**
     * @Route("/cart/add/{id}", name="cart_add", requirements={"id" : "\d+"})
     */
    public function add($id, CartService $cartService, ProductRepository $productRepository, Request $request): Response
    {

     // 0. securisation : est-ce que le produit il existe :
     $product = $productRepository->find($id);

     if(! $product ){
        throw $this->createNotFoundException("Le produit $id n'existe pas !");
     }

      $cartService->add($id);

      $this->addFlash('success',"Le produit a  bien ete ajoute au panier ");

      //dd($session);

      if($request->query->get('goToCart')){
        return $this->redirectToRoute('cart_show');
      }

      return $this->redirectToRoute('product_show',[
          'category_slug' => $product->getCategory()->getSlug(),
          'slug' => $product->getSlug()
      ]);
    }

    /**
     * @Route("/cart/{id}/delete", name="cart_delete", requirements={"id" : "\d+"})
     */
    public function delete($id, CartService $cartService){
      $cartService->remove($id);
      return $this->redirectToRoute('cart_show');
    }
    

     /**
     * @Route("/cart/{id}/decrement", name="cart_decrement", requirements={"id" : "\d+"})
     */
    public function decrement($id, CartService $cartService){
      $cartService->decrement($id);
      return $this->redirectToRoute('cart_show');
    }


    /**
     * @Route("/cart", name="cart_show")
     */
    public function show(CartService $cartService): Response{

    //  dd($detailCart);
      return $this->render('cart/index.html.twig', [
        'items' => $cartService->getDetailCartItems(),
        'total' => $cartService->getTotal()
      ]);
    }
}
