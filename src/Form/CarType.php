<?php

namespace App\Form;

use App\Entity\Car;
use Doctrine\DBAL\Types\ArrayType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class CarType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('model', TextType::class, [
                'label'  => 'Modèle',
                ])
            ->add('brand', TextType::class, [
                'label'  => 'Marque',
            ])
            ->add('year', DateType::class, [
                'label'  => 'Année de sortie',
            ])
            ->add('kilometers', NumberType::class, [
                'label'  => 'Kilométrage',
            ])
            ->add('licensePlate', TextType::class, [
                'label'  => 'Plaque d\'immatriculation',
            ])
            ->add('image', FileType::class, [
                'label'  => 'Image(s) du véhicule',
                'multiple' => true
            ])
            ->add('engine', TextType::class, [
                'label'  => 'Type de motorisation',
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
            ->add('gearbox', TextType::class, [
                'label'  => 'Types de boîtes de vitesses',
            ])
            ->add('title', TextType::class, [
                'label'  => 'Titre de l\'annonce',
            ])
            ->add('description', TextType::class, [
                'label'  => 'Description de l\'annonce',
            ])
            ->add('price', NumberType::class, [
                'label'  => 'Prix de la location par jour',
            ])
            ->add('category', TextType::class, [
                'label'  => 'Catégorie',
            ])
            ->add('city', TextType::class, [
                'label'  => 'Ville de départ',
            ])
            ->add('save', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Car::class,
        ]);
    }
}
