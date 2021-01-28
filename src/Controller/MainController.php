<?php

namespace App\Controller;

use App\Entity\Car;
use App\Entity\Rental;
use App\Form\RentalType;
use App\Form\SearchCarType;
use App\Repository\CarRepository;
use App\Repository\BrandRepository;
use App\Repository\ImagesRepository;
use App\Repository\RentalRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserInterface;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     */
    public function index(CarRepository $carRepository, Request $request): Response
    {
        // Formulaire de recherche de voiture
        //https://www.youtube.com/watch?v=_75fDJITerA
        $searchCarForm = $this->createForm(SearchCarType::class);
        if( $searchCarForm->handleRequest($request)->isSubmitted() && $searchCarForm->isValid() ) {
           
            $criteria = $searchCarForm->getData(); 
            $cars = $carRepository->searchCar($criteria);
            return $this->render('main/cars_list.html.twig',[
                'cars' => $cars,
            ]);
        }
        // https://stackoverflow.com/questions/10762538/how-to-select-randomly-with-doctrine
        $carsLastThree = $carRepository->findLastThreeCarsByDate();
        return $this->render('main/index.html.twig', [
            'carsLastThree' => $carsLastThree,
            'search_form' => $searchCarForm->createView(),
        ]);
    }

    /**
     * @Route("/redirect", name="app_redirect")
     */
    public function redirection(){
        $user = $this->getUser();

        if ($this->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('administrateur');
        }
        return $this->redirectToRoute('homepage');

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
    public function carsList(CarRepository $carRepository, Request $request): Response
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
    public function car(Car $car, Request $request, RentalRepository $rentalRepository, UserInterface $user = null): Response
    {
        // On instancie une rental que l'on va remplir en POST ) avec le createForm
        $rental = new Rental();
        $form = $this->createForm(RentalType::class, $rental);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            
            // On associe l'user à la car
            $rental->setUser($user);
            // On associe la voiture de l'annonce à la rental 
            $rental->setCar($car);

            // Calcul de la facture = prix de la loc par jour * nbr de jour de location
            $price = $car->getPrice();
            $diff = $rental->getEndingDate()->diff($rental->getStartingDate())->format("%a");
            $diff = intval($diff) + 1;
            // dd($diff);
            $rental->setBilling($diff * $price);

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
