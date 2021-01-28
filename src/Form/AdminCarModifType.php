<?php

namespace App\Form;

use App\Entity\Car;
use DateTime;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class AdminCarModifType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {


        $builder
            ->add('title')
            ->add('model')
            ->add('model')
            ->add('engine')
            ->add('seat')
            ->add('engine')
            ->add('gearbox')
            ->add('price')
            ->add('year', DateType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Car::class,
        ]);
    }
}
