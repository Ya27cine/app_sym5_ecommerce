<?php  

namespace App\Service;

use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CartService {

    /** @var  SessionInterface  */
    protected $session;

    protected $productRepository;
    public function __construct(SessionInterface  $session, ProductRepository $productRepository) 
    {
        $this->session = $session;
        $this->productRepository = $productRepository;
    }


    public function add(int $id){

        // 1. retrouver le panier, si n'existe pas alors prendre un tableau vide []
      $cart = $this->session->get('cart', []);

      // 2. voir si le produit $id existe deja :
      if(array_key_exists($id, $cart)){
        $cart[$id]++; // si c'est le cas, +1
      }else{
          $cart[$id] = 1; // sinon, ajouter le produit av la qnt 1
      }

      // enrigistrer le tableau mis a jour dans la session :
      $this->session->set('cart', $cart);
    }

    public function decrement(int $id){

    $cart = $this->session->get('cart', []);

    if($cart[$id] == 1){
      $this->remove($id); 
      return;
    }
    $cart[$id]--;
  
    $this->session->set('cart', $cart);
  }

    public function remove(int $id){
      $cart = $this->session->get('cart', []);

      unset($cart[$id]);

      $this->session->set('cart', $cart);
  }


  public function getDetailCartItems(){

      $detailCart = [];

      foreach($this->session->get('cart', []) as $id => $qty){
        $product = $this->productRepository->find($id);

        if( ! $product ) continue;

        $detailCart[] = [
          'product' => $product,
          'qty' => $qty,
          'total' => ( $product->getPrice() * $qty )
        ] ;

      }
    return $detailCart;
  }


  public function getTotal(){
    $total = 0;

    foreach($this->session->get('cart', []) as $id => $qty){
      $product = $this->productRepository->find($id);

      if( ! $product ) continue;

      $total +=  ( $product->getPrice() * $qty );

    }
   return $total;
  }


}



?>