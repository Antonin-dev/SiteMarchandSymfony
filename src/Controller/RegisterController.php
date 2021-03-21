<?php

namespace App\Controller;

use App\Classe\Mail;
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
        $notification = null;
        // Confirmationd'inscription
        $form = $this->createForm(RegisterType::class, $user);
        // Je creer une variable $form avec la fonction creation de formulaire en premier parametre elle prend le formulaire que l'on a crée et en second la création du user
        $form->handleRequest($request);
        // Le formulaire est capable d'ecouter la requette



        if ($form->isSubmitted() && $form->isValid()) {
            // si le formulaire a été submit et si le formulaire est valide avec les contraintes qu'on lui a mit je rentre dans la boucle
            $user = $form->getData();
            //J'injecte la data dans $user

            $searchEmail = $this->entityManager->getRepository(User::class)->findOneBy([
                'email' => $user->getEmail()
            ]);

            if (!$searchEmail) {
                $password = $encoder->encodePassword($user, $user->getPassword());
                // J'encore le mot de passe avec encode password il prend en premier parametre l'objet puis le mot de passe a crypté
                $user->setPassword($password);
                // Je set le password haché dans user

                $this->entityManager->persist($user);
                // Je fige les données
                $this->entityManager->flush();
                // J'envoie les données

                // Je fais le mail de confirmation
                $mail = new Mail();
                $content = "Bonjour ".$user->getFirstname()."</br> Bienvenue sur la premiere boutique dedié au made in France";
                $mail->send($user->getEmail(), $user->getFirstname(), 'Bienvenue sur La Boutique Francaise', $content);
                
                $notification = "Votre inscription s'est correctement déroulée. Vous pouvez dès à présent vous connecter à votre compte.";
            }else{
                $notification = "L'email que vous avez renseigné existe déja";
            }

            

           
           
            
        }



        return $this->render('register/index.html.twig', [
            'form' => $form->createView(),
            'notification' => $notification
            // J'envoie le $form avec createview pour creer le vue
        ]);
    }
}
