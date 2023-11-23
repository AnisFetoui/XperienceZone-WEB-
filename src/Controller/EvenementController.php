<?php

namespace App\Controller;

use App\Entity\Evenement;
use App\Form\EvenementType;
use App\Repository\EvenementRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\FileType;


#[Route('/evenement')]
class EvenementController extends AbstractController
{
    #[Route('/', name: 'app_evenement_index', methods: ['GET'])]
    public function index(EvenementRepository $evenementRepository): Response
    {
        return $this->render('evenement/index.html.twig', [
            'evenements' => $evenementRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_evenement_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $evenement = new Evenement();
        $form = $this->createForm(EvenementType::class, $evenement);
        $form->handleRequest($request);
        $imagedirectory = $this->getParameter('kernel.project_dir').'/public/uploads/images'; // Remplacez par le chemin réel de votre répertoire
        if ($form->isSubmitted() && $form->isValid()) {
            //handle image apload 
            $imageFile= $form->get('image')->getData();
            if ($imageFile){
                //generate a unique name for the image 
                $newFilename = uniqid().'.'.$imageFile->guessExtension();
                $imageFile->move( $imagedirectory,$newFilename);
                $evenement->setImage($newFilename);
            }
            $entityManager->persist($evenement);
            $entityManager->flush();

            return $this->redirectToRoute('app_evenement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('evenement/new.html.twig', [
            'evenement' => $evenement,
            'form' => $form,
        ]);
    }

    #[Route('/{idEvent}', name: 'app_evenement_show', methods: ['GET'])]
    public function show(Evenement $evenement): Response
    {
        return $this->render('evenement/show.html.twig', [
            'evenement' => $evenement,
        ]);
    }

    #[Route('/{idEvent}/edit', name: 'app_evenement_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Evenement $evenement, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(EvenementType::class, $evenement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_evenement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('evenement/edit.html.twig', [
            'evenement' => $evenement,
            'form' => $form,
        ]);
    }

    #[Route('/{idEvent}', name: 'app_evenement_delete', methods: ['POST'])]
    public function delete(Request $request, Evenement $evenement, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$evenement->getIdEvent(), $request->request->get('_token'))) {
            $entityManager->remove($evenement);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_evenement_index', [], Response::HTTP_SEE_OTHER);
    }

   /* #[Route('/evenements', name:'app_evenement_index', methods:['GET'])]
    
   public function indexx(Request $request, EvenementRepository $evenementRepository): Response
   {
       // Récupérer les paramètres de recherche depuis la requête
       $query = $request->query->get('query');
       $location = $request->query->get('location');

       // Utiliser le repository pour effectuer la recherche
       $evenements = $evenementRepository->search($query, $location);

       return $this->render('evenement/index.html.twig', [
           'evenements' => $evenements,
       ]);
   }*/

    #[Route('/search', name:'app_search')]

  public function search(Request $request): Response
  {
    $nomEvent = $request->query->get('nom_event');
    $lieuEvent = $request->query->get('lieu_event');

    // Ajoutez votre logique de recherche ici
    // Par exemple, recherchez des événements en fonction de la requête de recherche
    $em = $this->getDoctrine()->getManager();
    
    $evenements = $em->getRepository(Evenement::class)->findByNomAndLieu($nomEvent, $lieuEvent);

    return $this->render('evenement/index.html.twig', [
        'evenements' => $evenements,
        'nomEvent' => $nomEvent,
        'lieuEvent' => $lieuEvent,
    ]);
}
  }












