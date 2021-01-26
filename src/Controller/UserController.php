<?php

namespace App\Controller;

use App\Entity\Car;
use App\Repository\CarRepository;
use App\Repository\RentalRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
     * @Route("/mon-compte", name="user_account")
     */
    public function user_account(CarRepository $car, RentalRepository $rentals): Response
    {
        return $this->render('user/user_account.html.twig', [
            'cars' => $car->findAll(),
            'rentals' => $rentals->findAll(),
        ]);
    }
}
