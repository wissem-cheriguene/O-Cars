<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use function dd;


class AdminUserModifType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $user = $options['data']; /** @var User $user */

        $builder
            ->add('email')
            //->add('roles')
            ->add('firstName')
            ->add('lastName')
            ->add('birthdate', BirthdayType::class, [
                'label' => 'Date de naissance',
                'placeholder' =>[
                    'year' => 'année', 'month' => 'Mois', 'day' => 'Jour',
                ]
            ])
            ->add('address',TextType::class, ['label'=>'Adresse'])


        ;
        $builder->add('status', ChoiceType::class, [
            'choices'  => [
                '1' => 1,
                '2' => 2,
                '3' => 3,
            ],
            "attr"=>["class"=>"statusType"],
        ]);



        if($user->hasRole(User::ROLE_LOCATAIRE)) {
            $builder
                ->add('role', ChoiceType::class, [
                    'choices' => [
                        'propriétaire' => 'ROLE_PROPRIO',
                        'locataire' => 'ROLE_LOCATAIRE',
                    ],
                    "attr"=>["class"=>"roleType"],
                ]);
        }
    }



    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
