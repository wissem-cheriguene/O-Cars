<?php

namespace App\Controller;

use App\Entity\Car;
use App\Repository\CarRepository;
use App\Repository\RentalRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;


class UserController extends AbstractController
{
    /**
     * @Route("/mon-compte", name="user_account")
     */
    public function user_account(CarRepository $carRepo, RentalRepository $rentalsRepo, Request $request): Response
    {

        // Rental du locataire 
        $rentals = $rentalsRepo->findRentalsByUser($this->getUser());

        // Reservation Propriétaire (les demandes)
        $carsId = [];
        foreach($carRepo->findBy(['user' => $this->getUser()]) as $car) {
            $carsId[] = $car->getId();
        }        
        $bookings = $rentalsRepo->findOwnerByBookings($carsId);

        return $this->render('user/user_account.html.twig', [
            'cars' => $carRepo->findBy(['user' => $this->getUser()]),
            'bookings' => $bookings,
            'rentals' => $rentals,
        ]);
    }
}
