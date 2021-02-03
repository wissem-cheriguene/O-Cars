<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RecupPwdFormType;
use App\Form\RegistrationFormType;
use App\Security\EmailVerifier;
use App\Security\LoginFormAuthenticator;
use App\Service\EmailService;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;
use function dd;
use function dump;
use const FILTER_VALIDATE_EMAIL;

class RegistrationController extends AbstractController
{
    private $emailVerifier;
    /**
     * @var EmailService
     */
    private $emailService;

    public function __construct(EmailVerifier $emailVerifier, EmailService $emailService)
    {
        $this->emailVerifier = $emailVerifier;
        $this->emailService = $emailService;
    }

    public function newToken(){
        $bytes = random_bytes(15);
        return bin2hex($bytes);
    }


    /**
     * @Route("/register/{type}", name="app_register", requirements={
     *     "type"="proprietaire|locataire|admin"
     * })
     *
     */
    public function register($type, Request $request, UserPasswordEncoderInterface $passwordEncoder, EmailService $emailService): Response
    {

        $role = User::getRolefromString($type);
        $user = new User();

        $user->setRoles([$role]);
        $form = $this->createForm(RegistrationFormType::class, $user,[
            'which_role'=>$role,
        ]);
        //dd($user, $role);
        $user->setRole($role);


        $form->handleRequest($request);
        // dd($form->getData());
        if ($form->isSubmitted() && $form->isValid()) {

            // encode password avec une fonction de userInterface dont hérite notre entité user
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
            //$user->setEmail($request->query->get('email',''));

            // il faut donner une valeur au status ->car not null en bdd
            // équivalent à ça ->  $user->setStatus(2);
            $user->setStatus(User::STATUS_UNVERIFIED);

            $user->setToken($this->newToken());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);

            $entityManager->flush();
            $entityManager->persist($user);
            $entityManager->flush();

            $emailService->sendEmail([
                'to' => $user->getEmail(),
                'toName' => $user->getFirstname(),
                'template' => 'emails/verif.email.twig',
                'subject' => 'Vérifier votre adresse mail',
                'context' => [
                    'user'=> $user,
                ],
            ]);

            $this->addFlash('success', 'Merci de confirmer votre adresse mail');
            return $this->redirectToRoute('after_login');

        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
            'role'=> $role
        ]);
    }

    /**
     * @Route("/register-verification/{email}/{token}", name="app_register_verification")
     */
    public function verifMail(User $user , GuardAuthenticatorHandler $guardHandler, LoginFormAuthenticator $authenticator, Request $request){

        $user->setStatus(User::STATUS_ACTIF);
        $user->setToken($this->newToken());
        $em= $this->getDoctrine()->getManager();
        $em->flush();

        return $guardHandler->authenticateUserAndHandleSuccess(
            $user,
            $request,
            $authenticator,
            'main' // firewall name in security.yaml
        );

    }

    /**
     * @Route("/", name="app_homepage")
     */
    public function homepage(){
        return $this->render('general/homepage.html.twig');
    }

    /**
     * @Route("/inscription", name="app_loginPage")
     */
    public function loginpage(){
        return $this->render('registration/registerpage.html.twig');
    }



}
