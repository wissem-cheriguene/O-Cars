<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * @Route("/accueil", name="main")
     */
    public function index(): Response
    {
        return $this->render('login_form.html.twig', [
            'controller_name' => 'MainController',
        ]);
    }
}
