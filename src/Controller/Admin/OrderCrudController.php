<?php

namespace App\Controller\Admin;

use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Router\CrudUrlGenerator;

class OrderCrudController extends AbstractCrudController
{

    private $entityManagerInterface;
    private $crudUrlGenerator;

    public function __construct(EntityManagerInterface $entityManagerInterface, CrudUrlGenerator $crudUrlGenerator)
    {
        $this->entityManagerInterface = $entityManagerInterface;
        $this->crudUrlGenerator = $crudUrlGenerator;
    }
    

    public static function getEntityFqcn(): string
    {
        return Order::class;
    }

    public function configureActions(Actions $actions): Actions
    {

        $updatePreparation = Action::new('updatePreparation', 'Préparation en cours', 'fas fa-truck')->linkToCrudAction('updatePreparation');
        $updateDelivery = Action::new('updateDelivery', 'Livraison en cours', 'fas fa-box-open')->linkToCrudAction('updateDelivery');

        return $actions
            ->add('detail', $updatePreparation)
            ->add('detail', $updateDelivery)
            ->add('index', 'detail');
            // Permet d'ajouter l'onglet detail avec easyadmin
    }

    // fonction pour l'action de preparation de commande

    public function updatePreparation(AdminContext $context)
    {
        $order = $context->getEntity()->getInstance();
        $order->setState(2);
        $this->entityManagerInterface->flush();

        // Création d'un message flash pour la mis à jour

        $this->addFlash('notice', "<span style='color:green;'><strong>La commande " . $order->getReference() . " est bien <u> en cours de préparation</u></strong></span>");

        $url = $this->crudUrlGenerator->build()
            ->setController(OrderCrudController::class)
            ->setAction('index')
            ->generateUrl();

        return $this->redirect($url);
    }

    // fonction pour l'action de livraison de commande

    public function updateDelivery(AdminContext $context)
    {
        $order = $context->getEntity()->getInstance();
        $order->setState(3);
        $this->entityManagerInterface->flush();

        // Création d'un message flash pour la mis à jour

        $this->addFlash('notice', "<span style='color:orange;'><strong>La commande " . $order->getReference() . " est bien <u> en cours de livraison</u></strong></span>");

        $url = $this->crudUrlGenerator->build()
            ->setController(OrderCrudController::class)
            ->setAction('index')
            ->generateUrl();

        return $this->redirect($url);
    }

    public function configureCrud(Crud $crud): Crud
    {
        // Permet de trier les commande dans l'ordre DESC
        return $crud->setDefaultSort(['id' => 'DESC']);
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            DateField::new('createdAt', 'Passée le'),
            TextField::new('user.FullName', 'Client'),
            TextEditorField::new('delivery', 'Adresse de livraison')->onlyOnDetail(),
            // Permet de récuperer dans l'objet user la méthode getFullName
            MoneyField::new('total', 'Total produit')->setCurrency('EUR'),
            TextField::new('carrierName', 'Transporteur'),
            MoneyField::new('carrierPrice', 'Frais de port')->setCurrency('EUR'),
            ChoiceField::new('state')->setChoices([
                'Non paye' => 0,
                'Payée' => 1,
                'Préparation en cours' => 2,
                'Livraison en cours' => 3
            ]),
            ArrayField::new('orderDetails', 'Produits achetés')->hideOnIndex()
            // Permet de cacher cette ligne dans le index mais de l'avoir dans le detail 
        ];
    }
    
}
