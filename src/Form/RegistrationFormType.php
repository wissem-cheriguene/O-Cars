<?php

namespace App\Form;

use App\Entity\User;
use App\Repository\UserRepository;
use DateTime;
use Doctrine\ORM\EntityRepository;
use phpDocumentor\Reflection\Types\Null_;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use function date_date_set;
use function dd;
use function in_array;

class RegistrationFormType extends AbstractType
{
    /**
     * @var UserRepository
     */
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $role = $options['which_role'];

       if ($role ===User::ROLE_PROPRIO){
       }

       if ($role ===User::ROLE_LOCATAIRE){
       }


        $builder
            ->add('email')
            ->add('lastname',TextType::class, array('label' => 'Prénom'))
            ->add('firstName',TextType::class, array('label' => 'Nom'))
            ->add('birthdate', BirthdayType::class, [
                'label' => 'Date de naissance',
                'placeholder' =>[
                 'year' => 'année', 'month' => 'Mois', 'day' => 'Jour',
                ]
            ])
            ->add('address',TextType::class, ['label'=>'Adresse'])
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'label'=> "j'accepte les condition générales d'utilisation",
                'label_attr'=> ["class"=>'checkbox-custom'],
                'constraints' => [
                    new IsTrue([
                        'message' => 'vous devez accepter notre charte.',
                    ]),
                ],
            ])
            ->add('plainPassword', RepeatedType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'type' => PasswordType::class,
                'invalid_message' => 'Les deux mots de passe doivent être identiques.',
                'first_options'  => ['label' => 'Mot de passe'],
                'second_options' => ['label' => 'Répéter le mot de passe'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Entrez votre mot de passe',
                    ]),
                    new Length([
                        'min' => 3,
                        'minMessage' => 'Le mot de passe doit dépasser {{ limit }} caractères',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ]);



    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'which_role'=> null,
            'emailEnfant'=> null

        ]);
    }
}