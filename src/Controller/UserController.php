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
    public function user_account(CarRepository $carRepo, RentalRepository $rentalsRepo, PaginatorInterface $paginator, Request $request): Response
    {

        // Rental du locataire 
        $data = $rentalsRepo->findRentalsByUser($this->getUser());
        $rentals = $paginator->paginate(
            $data, // Requête contenant les données à paginer (ici nos rentals)
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            5 // Nombre de résultats par page
        );


        // Reservation Propriétaire (les demandes)
        $carsId = [];
        foreach($carRepo->findBy(['user' => $this->getUser()]) as $car) {
            $carsId[] = $car->getId();
        }        
        $bookings = $rentalsRepo->findOwnerByBookings($carsId);
        $notifications = [];
        foreach($bookings as $booking) {
            if($booking->getStatus() == 1) {
                $notifications[] = $booking;
            }
        }
        return $this->render('user/user_account.html.twig', [
            'cars' => $carRepo->findBy(['user' => $this->getUser()]),
            'bookings' => $bookings,
            'rentals' => $rentals,
            // 'notifications' => count($notifications),
        ]);
    }
}
