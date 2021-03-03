<?php

namespace App\Form;

use App\Entity\Institution;
use App\Entity\User;
use App\Repository\UserRepository;
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
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use function dd;
use function in_array;

class RecupPwdFormType extends AbstractType
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


        $builder
            ->add('email');







        $builder->addEventListener(FormEvents::POST_SET_DATA, function(FormEvent $event) use($options){
            $form= $event->getForm();

            if ($form->has('emailEnfant')){
                $form->get('emailEnfant')->setData($options['emailEnfant']);
            }

        });

        $builder->addEventListener(FormEvents::POST_SUBMIT, function(FormEvent $event){
            $form= $event->getForm();
            $user= $event->getData(); /** @var User $user */

            if ($form->has('expert')){
                $expert = $form->get('expert')->getData();
                $cp = $form->get('cp')->getData();
                $institution = $form->get('institution')->getData();
                if($institution->getExpert()!== $expert or $institution->getCp() !== $cp){
                    $form->get('cp')->addError(new FormError('Le code postal et le référent ne correspondent pas'));
                }
            }
            if($user->hasRole(User::ROLE_PARENT)){
                $enfant = $this->userRepository->findChildFromParentEmail($form->get('emailEnfant')->getData(), $user->getEmail());


                if($enfant){
                    $user->addEnfant($enfant);
                }
                else{
                    $form->get('emailEnfant')->addError(new FormError("Nous n'avons pas trouvé d'email correspondant"));
                }
            }

        });

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
