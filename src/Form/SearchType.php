<?php

namespace App\Form;

use App\Classe\Search;
use App\Entity\Category;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchType extends AbstractType
{
    // CREATION DU SIDE BAR RECHERCHER DANS LES PRODUITS

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('string', TextType::class, [
                'label' => false,
                'required' => false,
                'attr' => [
                    'placeholder' => 'Votre recherche',
                    'class' => 'form-control-sm'
                ]
            ])

            ->add('categories', EntityType::class, [
                'label' => false,
                'required' => false,
                'class' => Category::class,
                // Je lui passe la classe avec laquelle faire le lien
                'multiple' => true,
                // Choix multiple
                'expanded' => true
                // Checkbox
            ])

            ->add('submit', SubmitType::class, [
                'label' =>'Filtrer',
                'attr' => [
                    'class' => 'btn-block btn-info'
                ]
            ]);

            
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Search::class,
            'method' => 'GET',
            // Je recupere les données en lide GET
            'crsf_protection' => false, 
            // Pour gerer la protection du formulaire mais on à pas besoin pour la sidebar
        ]);
    }

    public function getBlockPrefix()
    {
        return '';
    }
}