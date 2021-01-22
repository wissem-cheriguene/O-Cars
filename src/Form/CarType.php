<?php

namespace App\Form;

use App\Entity\Car;
use App\Entity\Brand;
use Doctrine\DBAL\Types\ArrayType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class CarType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('model', TextType::class, [
                'label'  => 'Modèle du véhicule',
                ])
            ->add('brand', EntityType::class, [
                // looks for choices from this entity
                'class' => 'App\Entity\Brand',
                'label' => 'Marque du véhicule',
                // uses the Brand.username property as the visible option string
                'choice_label' => 'name',
            ])
            ->add('year', DateType::class, [
                'label'  => 'Année de sortie',
                'html5' => false,
                'widget' => 'single_text',
                'format' => 'y',
            ])
            ->add('kilometers', NumberType::class, [
                'label'  => 'Kilométrage',
            ])
            ->add('licensePlate', TextType::class, [
                'label'  => 'Plaque d\'immatriculation',
            ])
            ->add('images', FileType::class, [
                'mapped' => false,
                'label'  => 'Ajoutez des photos du véhicule',
                'multiple' => true,
                'attr'     => [
                    'accept' => 'image/*',
                ]
            ])
            ->add('engine', ChoiceType::class, [
                'label'  => 'Type de motorisation',
                'choices'  => [
                    'Diesel' => 'diesel',
                    'Essence' => 'essence',
                    'GPL' => 'gpl',
                    'Hybride' => 'hybride',
                ],
            ])
            ->add('seat', NumberType::class, [
                'label'  => 'Nombre de place(s)',
            ])
            ->add('horsePower', NumberType::class, [
                'label'  => 'Nombre de chevaux',
            ])
            ->add('color', TextType::class, [
                'label'  => 'Couleur(s) du véhicule',
            ])
            ->add('gearbox', ChoiceType::class, [
                'label'  => 'Transmission',
                'choices'  => [
                    'propulsion' => 'Propulsion',
                    'traction' => 'Traction',
                    'integrale' => '4x4',
                ],
            ])
            ->add('title', TextType::class, [
                'label'  => 'Titre de l\'annonce',
                'help' => 'Le titre ne doit pas dépasser 255 caractères'
            ])
            ->add('description', TextareaType::class, [
                'label'  => 'Description de l\'annonce',
            ])
            ->add('price', NumberType::class, [
                'label'  => 'Prix de la location par jour',
            ])
            ->add('category', EntityType::class, [
                // looks for choices from this entity
                'class' => 'App\Entity\Category',
                'label' => 'Catégorie du véhicule',
                // uses the Brand.username property as the visible option string
                'choice_label' => 'name',
            ])
            ->add('city', EntityType::class, [
                'label'  => 'Ville de départ',
                // looks for choices from this entity
                'class' => 'App\Entity\City',
                // uses the Brand.username property as the visible option string
                'choice_label' => 'name',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Car::class,
        ]);
    }
}
