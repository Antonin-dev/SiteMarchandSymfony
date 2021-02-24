<?php

namespace App\Controller;

use App\Form\ChangePasswordType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AccountPasswordController extends AbstractController
{

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/compte/modifier-mon-mot-de-passe", name="account_password")
     */
    public function index(Request $request, UserPasswordEncoderInterface $encoder): Response
    {
        $notification = null;

        $user = $this->getUser();
        $form =$this->createForm(ChangePasswordType::class, $user);

        $form->handleRequest($request);
    

        if ($form->isSubmitted() && $form->isValid()) {

            $old_pass= $form->get('old_password')->getData();
            // Je récupere l'ancien mot de passe
            if ($encoder->isPasswordValid($user, $old_pass)) {
                $new_password = $form->get('new_password')->getData();
                $password = $encoder->encodePassword($user, $new_password);
                // Je controle l'ancien mot de passe avec celui saisie
                $user->setPassword($password);
                // Je l'injecte dans password user
                $this->entityManager->flush();
                // J'envoie en bdd avec mis a jour
                $notification = 'Votre mot de passe à bien été mis à jour';
            }
            else{
                $notification = "Votre mot de passe actuel est pas bon";
            }
        }

        return $this->render('account/password.html.twig', [
            'form' => $form->createView(),
            'notification' => $notification
        ]);
    }
}
