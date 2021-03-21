<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Classe\Mail;
use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class OrderSuccessController extends AbstractController
{

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/commande/merci/{stripeSessionId}", name="order_validate")
     */
    public function index($stripeSessionId, Cart $cart): Response
    {
        $order = $this->entityManager->getRepository(Order::class)->findOneByStripeSessionId($stripeSessionId);
        
        if (!$order || $order->getUser() != $this->getUser()) {
            return $this->redirectToRoute('home');
        }
        
        if (!$order->getIsPaid()) {
            
            // Modifier le statis is pais de notre commande par 1
            $order->setIsPaid(1);
            $this->entityManager->flush();

            // Vider le panier
            $cart->remove();
        }

       
        

        // Envoyer un email à notre client pour lui confirmer sa commande
        $mail = new Mail();
        $content = "Bonjour ".$order->getUser()->getFirstname()."</br> Votre commande à bien été prise en compte";
        $mail->send($order->getUser()->getEmail(), $order->getUser()->getFirstname(), 'Votre commande la boutique francaise est bien validé', $content);
        // Afficher les informations de la commande de l'utilisateur

        return $this->render('order_success/index.html.twig', [
            'order' => $order
        ]);
    }
}
