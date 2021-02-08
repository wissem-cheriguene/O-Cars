<?php

namespace App\Controller;

use App\Entity\Rental;
use App\Repository\RentalRepository;
use App\Service\EmailService;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use function dump;

class RentalController extends AbstractController
{
    /**
     * Validation de la demande de location en BDD
     * @Route("/location-validation/{id}", name="rental_validate")
     */
    public function validate($id = null, EmailService $emailService): Response
    {
        $em = $this->getDoctrine()->getManager();

        $rental = $this->getDoctrine()
            ->getRepository(Rental::class)
            ->find($id);
        // dd($rental);
        $rental->setStatus(2);
        $em->persist($rental);
        $em->flush();
        // dd($rental);

        $locataire = $rental->getUser();

        $emailService->sendEmail([
            'to' => $locataire->getEmail(),
            'toName' => $locataire->getFirstname(),
            'template' => 'emails/accept.email.twig',
            'subject' => 'Location acceptée!',
            'context' => [
                'user'=> $locataire,
            ],
        ]);



        $this->addFlash('success', 'La location a bien été validée !');
        return $this->redirectToRoute('user_account');
    }

    /**
     * Refus de la demande de location en BDD
     * @Route("/location-refus/{id}", name="rental_refuse")
     */
    public function refuse($id = null, EmailService $emailService): Response
    {
        $em = $this->getDoctrine()->getManager();

        $rental = $this->getDoctrine()
            ->getRepository(Rental::class)
            ->find($id);
        $rental->setStatus(3);
        $em->persist($rental);
        $em->flush();
        // dd($rental);

        $locataire = $rental->getUser();

        $emailService->sendEmail([
            'to' => $locataire->getEmail(),
            'toName' => $locataire->getFirstname(),
            'template' => 'emails/refused.email.twig',
            'subject' => 'Location refusée',
            'context' => [
                'user'=> $locataire,
            ],
        ]);

        $this->addFlash('success', 'La location a bien été refusée !');
        return $this->redirectToRoute('user_account');
    }
}
