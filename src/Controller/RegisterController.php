<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegisterController extends AbstractController
{
    private $entityManager;
    // Je fais un atribut doctrine

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }


    /**
     * @Route("/inscription", name="register")
     */
    public function index(Request $request, UserPasswordEncoderInterface $encoder): Response
    // Je demande a la fonction d'injecter request et userpasswordinterface
    {
        $user = new User;
        // Je creer un nouveau user avec l'import (use)

        $form = $this->createForm(RegisterType::class, $user);
        // Je creer une variable $form avec la fonction creation de formulaire en premier parametre elle prend le formulaire que l'on a crée et en second la création du user

        $form->handleRequest($request);
        // Le formulaire est capable d'ecouter la requette



        if ($form->isSubmitted() && $form->isValid()) {
            // si le formulaire a été submit et si le formulaire est valide avec les contraintes qu'on lui a mit je rentre dans la boucle
            $user = $form->getData();
            //J'injecte la data dans $user

            $password = $encoder->encodePassword($user, $user->getPassword());
            // J'encore le mot de passe avec encode password il prend en premier parametre l'objet puis le mot de passe a crypté
            $user->setPassword($password);
            // Je set le password haché dans user

            $this->entityManager->persist($user);
            // Je fige les données
            $this->entityManager->flush();
            // J'envoie les données
            
        }



        return $this->render('register/index.html.twig', [
            'form' => $form->createView()
            // J'envoie le $form avec createview pour creer le vue
        ]);
    }
}
