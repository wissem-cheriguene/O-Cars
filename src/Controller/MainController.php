<?php

namespace App\Controller;

use App\Entity\Car;
use App\Repository\BrandRepository;
use App\Repository\CarRepository;
use App\Repository\ImagesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     */
    public function index(CarRepository $carRepository, ImagesRepository $imagesRepository): Response
    {
        $cars = $carRepository->findAll();
        $images = $imagesRepository->findAll();
        dump($images);
        return $this->render('main/index.html.twig', [
            'cars' => $cars,
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
     * Liste des voitures 
     * @Route("/voitures", name="cars_list")
     */
    public function carsList(CarRepository $carRepository): Response
    {
        // Toutes les voitures
        $car = $carRepository->findAll(['model' => 'ASC']);

        return $this->render('main/cars_list.html.twig',[
            'cars' => $car,
        ]);
        
    }
    /**
     * Affichage d'une annonce
     * @Route("/voiture/{id}", name="car", methods={"GET", "POST"})
     */
    public function car(Car $car): Response
    {
        return $this->render('main/car.html.twig',[
            'car' => $car,
        ]);
    } 
}
