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
    public function user_account(CarRepository $car, RentalRepository $rentals, PaginatorInterface $paginator, Request $request): Response
    {
        $data = $rentals->findAll();

        $rentals = $paginator->paginate(
            $data, // Requête contenant les données à paginer (ici nos rentals)
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            5 // Nombre de résultats par page
        );

        $user = $this->getUser();
        $car = $user->getCars();

        return $this->render('user/user_account.html.twig', [
            'cars' => $car,
            'rentals' => $rentals,
        ]);
    }
}
