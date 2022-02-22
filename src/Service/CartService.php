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

    protected function getCart() : array{
      return $this->session->get('cart', []);
    }

    protected function setCart(array $cart){
      $this->session->set('cart', $cart);
    }


    public function add(int $id){

        // 1. retrouver le panier, si n'existe pas alors prendre un tableau vide []
      $cart = $this->getCart();

      // 2. voir si le produit $id n existe pas :
      if(! array_key_exists($id, $cart)){
        $cart[$id] = 0; // si c'est le cas on va init par 0
      }
      
      $cart[$id]++; // dans tt les cas on va rajoute 1.
      
      // enrigistrer le tableau mis a jour dans la session :
      $this->setCart($cart);
    }

    public function decrement(int $id){

    $cart = $this->getCart();

    if($cart[$id] == 1){
      $this->remove($id); 
      return;
    }
    $cart[$id]--;
  
    $this->setCart($cart);
  }

    public function remove(int $id){
      $cart = $this->getCart();

      unset($cart[$id]);

      $this->setCart($cart);
  }


  public function getDetailCartItems(){

      $detailCart = [];

      foreach($this->getCart() as $id => $qty){
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

    foreach($this->getCart() as $id => $qty){
      $product = $this->productRepository->find($id);

      if( ! $product ) continue;

      $total +=  ( $product->getPrice() * $qty );

    }
   return $total;
  }


}

?>