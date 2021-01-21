<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     */
    public function index(): Response
    {
        return $this->render('main/index.html.twig', [
            'controller_name' => 'MainController',
        ]);
    }

    /**
     * @Route("/nous-contactez", name="about")
     */
    public function about(): Response
    {
        return $this->render('main/about.html.twig', [
            'controller_name' => 'MainController',
        ]);
    }

    /**
     * @Route("/cgu", name="cgu")
     */
    public function cgu(): Response
    {
        return $this->render('main/cgu.html.twig',[
            'controller_name' => 'MainController',
        ]);

    }

    /**
     * @Route("/voiture", name="cars_list")
     */
    public function carsList(): Response
    {
        return $this->render('main/cars_list.html.twig',[
            'controller_name' => 'MainController',
        ]);
    }

    /**
     * @Route("/voiture/{id}, name="car")
     */
    /* 
    public function car(): Response
    {
        return $this->render('main/car.html.twig',[
            'controller_name' => 'MainController',
        ]);
    } 
    */
}
