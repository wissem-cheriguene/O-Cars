<?php

namespace App\Controller;

use App\Entity\Car;
use App\Entity\User;
use App\Form\AdminUserModifType;
use App\Form\CarType;
use App\Entity\Images;
use App\Repository\ImagesRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CarController extends AbstractController
{


    /**
     * @Route("/back/voiture/ajout", name="car_add", methods={"GET","POST"})
     * @param Request $request
     * @return Response
     */
    public function add(Request $request): Response
    {
        $car = new Car();
        $form = $this->createForm(CarType::class, $car);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {

            // On récupère les images transmises
            $images = $form->get('images')->getData();

            foreach ($images as $image) {
                
                // On génère un nouveau nom de fichier
                $fichier = md5(uniqid()).'.'.$image->guessExtension();
                // On copie le fichier dans le dossier uploads (définit dans servies.yaml)
                $image->move(
                    $this->getParameter('images_directory'),
                    $fichier
                );
                // On crée l'image dans la base de données
                $img = new Images();
                $img->setName($fichier);
                $car->addImage($img);
            }


            $car->setUser($this->getUser());
            // faire quelque chose avec l'entité, par exemple la sauvegarder en bdd
            $em = $this->getDoctrine()->getManager();
            $em->persist($car);
            $em->flush();

            $this->addFlash(
                'success',
                'Voiture enregistrée!'
            );

            // toujours rediriger vers une page après un POST réussi
            return $this->redirectToRoute('user_account');
        }

        return $this->render('car/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/back/voiture/modification/{id<\d+>}", name="car_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Car $car = null, EntityManagerInterface $em, ImagesRepository $imageRepo): Response
    {
        if (!$car) {
            throw $this->createNotFoundException(
                'No car found for this id !'
            );
        }

        // On crée le form
        $form = $this->createForm(CarType::class, $car);

        // Le form gère la requête
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // On récupère les images transmises
            $images = $form->get('images')->getData();

            foreach ($images as $image) {

                // On vide les anciennes img de la BDD avant d'ajouter les nouvelles
                $oldImages = $imageRepo->findBy(['car' => $car]);
                foreach($oldImages as $oneImage) {
                    $car->removeImage($oneImage);
                }
                // On génère un nouveau nom de fichier
                $fichier = md5(uniqid()).'.'.$image->guessExtension();
                // On copie le fichier dans le dossier uploads (définit dans servies.yaml)
                $image->move(
                    $this->getParameter('images_directory'),
                    $fichier
                );

                // On crée l'image dans la base de données
                $img = new Images();
                $img->setName($fichier);
                $car->addImage($img);
            }

            $em->flush();

            $this->addFlash('success', 'L\'annonce '.$car->getTitle().' a bien été modifiée !');

            return $this->redirectToRoute('user_account');
        }

        return $this->render('car/edit.html.twig', [
            'form' => $form->createView(),
            'car' => $car
        ]);
    }

    /**
     * @Route("/back/voiture/delete/{id<\d+>}", name="car_delete", methods={"DELETE"})
     */
    public function delete(EntityManagerInterface $entityManager, Car $car = null, Request $request): Response
    {
        if (!$car) {
            throw $this->createNotFoundException(
                'No car found for this id '
            );
        }

        //on récupère le token soumis
        $submittedToken = $request->request->get('token');

        // on vérifie s'il est présent et le même que celui mis en session lors de l'appel à csrf_token() dans la création du formulaire
        if ($this->isCsrfTokenValid('delete-car', $submittedToken)) {
            $entityManager->remove($car);
            $entityManager->flush();
            
            $this->addFlash('success', 'La voiture "'. $car->getTitle() .'" a bien été supprimé.');

            return $this->redirectToRoute('user_account');
        }

        // si token non valide
        $this->addFlash('danger', 'Problème à l\'enregistrement, veuillez renvoyer le formulaire.');

        return $this->redirectToRoute('user_account');
    }

}
