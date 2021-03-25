<?php

namespace App\Controller;

use App\Classe\Mail;
use App\Entity\Header;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{

    private $entityManagerInterface;

    public function __construct(EntityManagerInterface $entityManagerInterface)
    {
        $this->entityManagerInterface = $entityManagerInterface;
    }

    /**
     * @Route("/", name="home")
     */
    public function index(): Response
    {
        $products = $this->entityManagerInterface->getRepository(Product::class)->findBy([
            'isBest' => 1
        ]);

        $headers = $this->entityManagerInterface->getRepository(Header::class)->findAll();
     


        return $this->render('home/index.html.twig', [
            'products' => $products,
            'headers' => $headers
        ]);
    }
}
