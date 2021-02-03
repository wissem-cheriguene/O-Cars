<?php

namespace App\Form;

use App\Entity\User;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Validator\Constraints\Length;

use Symfony\Component\Validator\Constraints\NotBlank;
use function dd;

class ModifProfilType extends AbstractType
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $user = $options['data']; /** @var User $user */
        $builder
            // ->add('plainPassword', RepeatedType::class, [
            //     // instead of being set onto the object directly,
            //     // this is read and encoded in the controller
            //     'mapped' => false,
            //     'required'=>false,
            //     'type' => PasswordType::class,
            //     'invalid_message' => 'Les deux mots de passe doivent être identiques.',
            //     'first_options'  => ['label' => 'Mot de passe'],
            //     'second_options' => ['label' => 'Répéter le mot de passe'],
            //     'constraints' => [
            //         new Length([
            //             'min' => 3,
            //             'minMessage' => 'Le mot de passe doit dépasser {{ limit }} caractères',
            //             // max length allowed by Symfony for security reasons
            //             'max' => 4096,
            //         ]),
            //     ],
            // ])
            ->add('email')
            ->add('lastname',TextType::class, array('label' => 'Nom'))
            ->add('firstname',TextType::class, array('label' => 'Prénom'))
            ->add('username',TextType::class, array('label' => 'Pseudo'))
            ->add('birthdate', BirthdayType::class, [
                'label' => 'Date de naissance',
                'placeholder' =>[
                    'year' => 'année', 'month' => 'Mois', 'day' => 'Jour',
                ]
            ])
            ->add('address',TextType::class, ['label'=>'Adresse'])

        ;



        // $builder->addEventListener(FormEvents::POST_SUBMIT, function(FormEvent $event){
        //     $form =$event->getForm();
        //     $user = $event->getData();/** @var User $user */
        //     if($plainPassword= $form->get('plainPassword')->getData()){
        //         $user->setPassword($this->passwordEncoder->encodePassword($user, $plainPassword));
        //     }
        // });
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
