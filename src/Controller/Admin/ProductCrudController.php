<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ProductCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Product::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return[

            TextField::new('name'),
            SlugField::new('slug')->setTargetFieldName('name'),
            ImageField::new('illustration')
            ->setBasePath('uploads/')
            // indiquer la direction de lecture
            ->setUploadDir('public/uploads/')
            // indique la direction de l'upload
            ->setUploadedFileNamePattern('[randomhash].[extension]')
            // modifie le nom de facon aleatoire pour le fichier
            ->setRequired(false),
            TextField::new('subtitle'),
            TextareaField::new('description'),
            BooleanField::new('isBest'),
            MoneyField::new('price')->setCurrency('EUR'),
            AssociationField::new('category')
        ];
    }
    
}


