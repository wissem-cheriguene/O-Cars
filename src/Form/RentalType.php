<?php

namespace App\Form;

use App\Entity\Rental;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class RentalType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // https://stackoverflow.com/questions/58562896/symfony-4-flatpickr-parse-date
        $builder
            ->add('startingDate', DateTimeType::class, [
                'label' => "Date de début",
                'format' => 'yyyy-MM-dd',
                'html5' => false,
                'widget' => 'single_text',
                'attr'=> [
                    'placeholder' => "Du",
                    "class" => "flatpickr"
                ],
            ])
            ->add('endingDate', DateTimeType::class, [
                'label' => "Date de fin",
                'format' => 'yyyy-MM-dd',
                'html5' => false,
                'widget' => 'single_text',
                'attr'=> [
                    'placeholder' => "Au",
                    "class" => "flatpickr",
                    "type" => 'text',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Rental::class,
        ]);
    }
}
