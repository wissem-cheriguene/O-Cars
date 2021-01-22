<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ModifProfilType;
use App\Form\RegistrationFormType;
use App\Form\ResetPassType;
use App\Repository\UserRepository;
use App\Service\EmailService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\Request;
use App\Security\PwdFormAuthenticator;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;

use function bin2hex;
use function dump;
use function random_bytes;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils)
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error'         => $error,
        ]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        throw new \Exception('Interception! - try/catch');
    }


    /**
     * @Route("/recuperation-mot-de-passe", name="app_forgotten_password")
     */
    public function oubliPass(Request $request, UserRepository $users, EmailService $emailService, UserPasswordEncoderInterface $passwordEncoder
    ): Response
    {
        // On initialise le formulaire
        $form = $this->createForm(ResetPassType::class);

        // On traite le formulaire
        $form->handleRequest($request);

        // Si le formulaire est valide
        if ($form->isSubmitted() && $form->isValid()) {
            // On récupère les données
            $donnees = $form->getData();

            // On cherche un utilisateur ayant cet e-mail
            $user = $users->findOneByEmail($donnees['email']);

            // Si l'utilisateur n'existe pas
            if ($user === null) {
                // On envoie une alerte disant que l'adresse e-mail est inconnue
                $this->addFlash('danger', 'Cette adresse e-mail est inconnue');

                // On retourne sur la page de connexion
                return $this->redirectToRoute('app_login');
            }

            // On génère un token
            $token = $this->newToken() ;

            // On essaie d'écrire le token en base de données

            $user->setToken($this->newToken());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);


            $entityManager->flush();


            // On génère l'e-mail

            $emailService->sendEmail([
                'to' => $user->getEmail(),
                'toName' => $user->getFirstname(),
                'template' => 'emails/recup.email.twig',
                'subject' => 'Récuperation du mot de passe',
                'context' => [
                    'user'=> $user,
                ],
            ]);

            // On crée le message flash de confirmation
            $this->addFlash('success', 'E-mail de réinitialisation du mot de passe envoyé !');

            // On redirige vers la page de login
            return $this->redirectToRoute('app_login');
        }

        // On envoie le formulaire à la vue
        return $this->render('security/forgotten_password.html.twig',['emailForm' => $form->createView()]);
    }



    public function newToken(){
        $bytes = random_bytes(15);
        return bin2hex($bytes);
    }



    /**
     * @Route("/register-verification-recup/{email}/{token}", name="app_register_verification_recup")
     */
    public function verifMailrecup(User $user , GuardAuthenticatorHandler $guardHandler, PwdFormAuthenticator $authenticator, Request $request){

        $user->setToken($this->newToken());
        $em= $this->getDoctrine()->getManager();
        $em->flush();

        $this->addFlash('success', 'Vous pouvez maintenant changer votre mot de passe');

        return $guardHandler->authenticateUserAndHandleSuccess(
            $user,
            $request,
            $authenticator,
            'main' // nom du firewall dans security.yaml
        );


    }


    /**
     * @Route("/after-login", name="after_login")
     */
    public function afterLogin()
    {
        return $this->render('security/afterLogin.html.twig');
    }




    /**
     * @Route("/modifier-profil", name="modifProfil")
     */
    public function editUser(Request $request){
        $user = $this->getUser();
        $form=$this->createForm(ModifProfilType::class, $user);
        $form->handleRequest($request);
        if($form->isSubmitted() and $form->isValid()){
            $em=$this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush($user);
            $this->addFlash('success', 'Votre mot de profil à été modifié');
            return $this->redirectToRoute('app_homepage');

        }
        return $this->render('user/editUser.html.twig',[
            'form'=>$form->createView()
        ]);
    }


}
