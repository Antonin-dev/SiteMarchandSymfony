<?php

namespace App\Classe;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class Cart
{

    private $session;
    private $entityManager;



    public function __construct(SessionInterface $session, EntityManagerInterface $entityManager)
    {
        $this->session = $session;
        $this->entityManager = $entityManager;
    }




    public function add ($id)
    {
        $cart = $this->session->get('cart', []);

        if (!empty($cart[$id])) {
            $cart[$id]++;
        }

        else{
            $cart[$id] = 1;
        }

        $this->session->set('cart',$cart);
    }


    public function get()
    {
        return $this->session->get('cart');
    }

    public function remove()
    {
        // Je supprime le panier complet
        return $this->session->remove('cart');
    }

    public function delete ($id)
    {
        // Je supprime un article du panier
        $cart = $this->session->get('cart', []);
        // J'enleve de la session
        unset($cart[$id]);
        // Je retourne et je resset la session avec les valeurs 
        return $this->session->set('cart',$cart);
    }

    
    // Reduire la quantité
    public function decrease($id)
    {
        $cart = $this->session->get('cart', []);

        if ($cart[$id] > 1) {

            $cart[$id]--;

        }
        else{

            unset($cart[$id]);
        }
        return $this->session->set('cart',$cart);

    }

    // Function qui permet de recupere le panier compleet
    public function getFull()
    {
        $cartComplete = [];
        
        if ($this->get()) {
            

            foreach ($this->get() as $id => $quantity) {

                $product_object = $this->entityManager->getRepository(Product::class)->findOneBy([
                    'id' => $id 
                ]);

                if (!$product_object) {
                    $this->delete($id);
                    continue;
                }

                $cartComplete[] = [
                    'product' => $product_object,
                    'quantity' => $quantity
                ];
            }
        }
        return $cartComplete;
    }

}