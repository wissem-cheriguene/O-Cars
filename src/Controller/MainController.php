<?php

namespace App\Controller;

use App\Entity\Car;
use App\Entity\Rental;
use App\Form\RentalType;
use App\Repository\CarRepository;
use App\Repository\BrandRepository;
use App\Repository\ImagesRepository;
use App\Repository\RentalRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

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
    public function car(Car $car, Request $request, RentalRepository $rentalRepository): Response
    {
        $rental = new Rental();
        $form = $this->createForm(RentalType::class, $rental);
        // dd($request);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            
            // dd($request);
            // On associe la voiture de l'annonce à la rental 
            $rental->setCar($car);

            // faire quelque chose avec l'entité, par exemple la sauvegarder en bdd
            $em = $this->getDoctrine()->getManager();
            $em->persist($rental);
            $em->flush();

            $this->addFlash(
                'success',
                'Location enregistrée!'
            );

            // toujours rediriger vers une page après un POST réussi
            return $this->redirectToRoute('cars_list');
        } 



        return $this->render('main/car.html.twig',[
            'car' => $car,
            'form' => $form->createView(),
            // Recupérer les rentals en BDD et les envoyées à la vue
            'rentals' => $rentalRepository->findRentalsByCar($car)
        ]);
    }
}
