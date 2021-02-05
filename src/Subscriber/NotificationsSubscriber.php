<?php

namespace App\Subscriber;

use App\Repository\CarRepository;
use App\Repository\RentalRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Security;

class NotificationsSubscriber implements EventSubscriberInterface
{
    public function __construct(EntityManagerInterface $em, Security $security) {
        $this->em = $em;
        $this->security = $security;
    }
    public function onKernelResponse(ResponseEvent $event)
    {
        // Nombre de notifications en attente de traitement affiché sur la nav
        $currentUser = $this->security->getUser();
        if($currentUser === null) {};

        $carRepo = $this->em->getRepository("App:Car");
        $rentalsrepo = $this->em->getRepository("App:Rental");
        $carsId = [];
        foreach($carRepo->findBy(['user' => $currentUser]) as $car) {
            $carsId[] = $car->getId();
        }  
        $bookings = $rentalsrepo->findOwnerByBookings($carsId);
        $notifications = [];
        foreach($bookings as $booking) {
            if($booking->getStatus() == 1) {
                $notifications[] = $booking;
            }
        }
        $notifications = strval(count($notifications));

        // La réponse est dans l'event
        $response = $event->getResponse();
        // On peut récupérer son contenu (corps de la réponse)
        $content = $response->getContent();

        // On souhaite "injecter" la bannière dans le HTML
        // Si on a une balise body
        // On a une balise body avec un id optionnel
        // on utilise une Regex pour matcher cette balise body
        if (preg_match('/<span class="badge bg-danger">/', $content, $match)) {
            // On récupère la balise body complète (avec son id)
            $span = $match[0];
            // On remplace cette balise par la même balise + la bannière
            $newContent = str_replace($span, $span . '<span class="badge bg-danger">'.$notifications.'</span>', $content);
            // On écrase le contenu de la réponse
            $response->setContent($newContent);
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            'kernel.response' => 'onKernelResponse',
        ];
    }
}