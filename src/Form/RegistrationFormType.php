<?php

namespace App\Form;

use DateTime;
use function dd;
use App\Entity\User;
use function in_array;
use function date_date_set;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use phpDocumentor\Reflection\Types\Null_;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

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
            ->add('lastname',TextType::class, array('label' => 'Nom'))
            ->add('firstname',TextType::class, array('label' => 'Prénom'))
            ->add('username',TextType::class, array('label' => 'Pseudo'))
            ->add('birthdate', DateType::class, [
                'label' => 'Date de naissance',
                'placeholder' =>[
                 'year' => 'année', 'month' => 'Mois', 'day' => 'Jour',
                ],
                'invalid_message' => 'Valeur incorrect'
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
