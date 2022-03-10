<?php  

namespace App\Controller\Purchase;

use App\Repository\PurchaseRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PurchasePaymentController extends AbstractController {

    /**
     * @Route("/purchase/pay/{id}", name="purchase_payment_form")
     */
        public function showCardForm($id, PurchaseRepository $purchaseRepository){

            $purchase = $purchaseRepository->find($id);

// This is your test secret API key.
\Stripe\Stripe::setApiKey('sk_test_51KXlDaHaSfa42x4ZIMedqAm9dfs8hKwv0XLBxUpWMcaHFChurlUrk0K7sKhAcAajROrSuUO4M66ghajHSxnYFt5W00DBKhz0io');



             // Create a PaymentIntent with amount and currency

            $paymentIntent = \Stripe\PaymentIntent::create([
                'amount' => $purchase->getTotal(),
                'currency' => 'eur',
            ]);
            return $this->render('purchase/payment.html.twig',  [
                "clientSecret" => $paymentIntent->client_secret,
                "purchase" => $purchase
            ]);
        }

}



?>