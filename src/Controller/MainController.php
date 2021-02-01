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
use Knp\Component\Pager\PaginatorInterface;
use Knp\Bundle\SnappyBundle\KnpSnappyBundle;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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
            $this->container->get('session')->set('criteria', $criteria);
            return $this->redirectToRoute('cars_list');

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
     * @Route("/facture/{rentalId}", name="billing")
     */
    public function cgu(\Knp\Snappy\Pdf $knpSnappyPdf, $rentalId): Response
    {
        $em = $this->getDoctrine()->getManager();

        $rental = $this->getDoctrine()
            ->getRepository(Rental::class)
            ->find($rentalId);
        
            if($rental->getStatus() != 2) {
                $this->addFlash(
                    'danger',
                    'Petit malin ! :]'
                );    
                return $this->redirectToRoute('user_account');
            };
            
        $html = $this->renderView('pdf/pdf.html.twig', array(
            'rental'  => $rental
        ));
        
        $start = $rental->getStartingDate()->format('Y-m-d');
        $end = $rental->getEndingDate()->format('Y-m-d');
        return new PdfResponse(
            $knpSnappyPdf->getOutputFromHtml($html),
            'facture_du_'. $start . '_au_'. $end .'.pdf'
        );

    }

    /**
     * Liste des voitures 
     * @Route("/voitures", name="cars_list")
     */
    public function carsList(CarRepository $carRepository, Request $request,PaginatorInterface $paginator): Response
    {
        // On récupère la variable des voitures stockés en sessions	
        $sessionCriteria = $this->container->get('session')->get('criteria');
        // Si la variable existe
        if($sessionCriteria) {	
            $data = $carRepository->searchCar($sessionCriteria);	
            // dump($data);	
            // dump($carRepository->findAll(['model' => 'ASC']));	
        }	
        else {	
            // au sinon on envoie la requête findAll()	
            $data = $carRepository->findAll(['model' => 'ASC']);	
        }	
        // dump($data);	
        $cars = $paginator->paginate(	
            $data, // Requête contenant les données à paginer (ici nos rentals)	
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page	
            9 // Nombre de résultats par page	
        );	
        // dump($cars->getTotalItemCount());	
        $this->container->get('session')->remove('criteria');	
        // dump($sessionCriteria);	

        return $this->render('main/cars_list.html.twig',[
            'cars' => $cars,
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
            
            if($user === null) {
                // dd($user);
                return $this->redirectToRoute('app_login');
            }
            // On associe l'user LOCATAIRE à la car
            $rental->setUser($user);
            // On associe la voiture de l'annonce à la rental 
            $rental->setCar($car);
            
            // Mettre le statut de la rental à 1 (en attente de validation)
            $rental->setStatus(1);
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
                'Demande de location enregistrée!'
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
