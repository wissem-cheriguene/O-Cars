<?php

namespace App\Form;

use App\Entity\Car;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class SearchCarType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('model', TextType::class, [
            'label'  => 'Modèle du véhicule',
            'required' => false,
            ])
        ->add('brand', EntityType::class, [
            // looks for choices from this entity
            'class' => 'App\Entity\Brand',
            'label' => 'Marque du véhicule',
            // uses the Brand.username property as the visible option string
            'choice_label' => 'name',
        ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        // $resolver->setDefaults([
        //     'data_class' => Car::class,
        // ]);
    }
}
