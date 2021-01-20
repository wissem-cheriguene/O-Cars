<?php

namespace App\Controller;

use App\Entity\Car;
use App\Form\CarType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    /**
     * @Route("/back/voiture/ajout", name="admin_car_add", methods={"GET","POST"})
     */
    public function index(Request $request): Response
    {
        $car = new Car();
        $form = $this->createForm(CarType::class, $car);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            // $form->getData() contient les données soumises
            // mais la variable `$car` a également été mise à jour
            $car = $form->getData();
            // dd($car);

            // faire quelque chose avec l'entité, par exemple la sauvegarder en bdd
            $em = $this->getDoctrine()->getManager();
            $em->persist($car);
            $em->flush();

            // toujours rediriger vers une page après un POST réussi
            return $this->redirectToRoute('car_add_success');
        }

        return $this->render('admin/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/back/voiture/success", name="car_add_success", methods={"GET"})
     */
    public function car_add_sucess(): Response
    {
        return $this->render('admin/car_add_success.html.twig');
    }
}
