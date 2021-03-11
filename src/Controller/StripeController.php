<?php

namespace App\Controller;

use App\Classe\Cart;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

class StripeController extends AbstractController
{
    /**
     * @Route("/commande/create-session", name="stripe_create_session")
     */
    public function index(Cart $cart)
    {
            Stripe::setApiKey('sk_test_51ITYOqLrvHf8f07ZiyCyAwukbdXimRfUcglVFAbObvtPorKUI4L4zVvZBlgKW58qYh54uO6sNpHBTydiRQsUP6LJ00jWZKfXgH');

            $products_for_stripe = [];
            $YOUR_DOMAIN = 'http://localhost:8000';

            foreach ($cart->getFull() as $product) {
                $products_for_stripe[] = [
                    'price_data' => [
                    'currency' => 'eur',
                    'unit_amount' => $product['product']->getPrice(),
                    'product_data' => [
                        'name' => $product['product']->getName(),
                        'images' => [$YOUR_DOMAIN.'/uploads/'.$product['product']->getIllustration()],
                        ],
                    ],
                'quantity' => $product['quantity']
                ];
            };
            $checkout_session = Session::create([
                'payment_method_types' => ['card'],
                'line_items' => [
                    $products_for_stripe
                ],
                'mode' => 'payment',
                'success_url' => $YOUR_DOMAIN . '/success.html',
                'cancel_url' => $YOUR_DOMAIN . '/cancel.html',
                ]);
            
        $response = new JsonResponse(['id' => $checkout_session->id]);

        return $response;
        
    }
}