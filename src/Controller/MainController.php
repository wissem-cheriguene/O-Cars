<?php

namespace App\Controller;

use App\Repository\BrandRepository;
use App\Repository\CarRepository;
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
     * @Route("/voitures", name="cars_list")
     */
    public function carsList(CarRepository $carRepository, BrandRepository $brandRepository): Response
    {
             // Toutes les voitures
             $car = $carRepository->findAll(['model' => 'ASC']);

             

             // Toutes les marques
             $brand = $brandRepository->findAll(['name' => 'ASC']);

             dump($brand);

        return $this->render('main/cars_list.html.twig',[
            'controller_name' => 'MainController',
            'cars' => $car,
            'brand' => $brand,
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
