<?php

namespace App\Controller;

use App\Entity\Reclamations;
use App\Form\ReclamationsType;
use App\Repository\ReclamationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;


#[Route('/reclamations')]
class ReclamationsController extends AbstractController
{
    #[Route('/nacer', name: 'app_reclamations_index', methods: ['GET'])]
    public function index(ReclamationRepository $reclamationRepository): Response
    {
        return $this->render('reclamations/index.html.twig', [
            'reclamations' => $reclamationRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_reclamations_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $reclamation = new Reclamations();
        $form = $this->createForm(ReclamationsType::class, $reclamation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($reclamation);
            $entityManager->flush();

            return $this->redirectToRoute('app_reclamations_new', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('reclamations/new.html.twig', [
            'reclamation' => $reclamation,
            'form' => $form,
        ]);
    }

    #[Route('show/{idr}', name: 'app_reclamations_show', methods: ['GET', 'POST'])]
    public function show(Reclamations $reclamation): Response
    {
        return $this->render('reclamations/show.html.twig', [
            'reclamation' => $reclamation,
        ]);
        
    }

    #[Route('/details/{idr}', name: 'app_reclamations_details', methods: ['GET'])]
    public function details(string $idr, ReclamationRepository $reclamationRepository): Response
    {
         $reclamation = $reclamationRepository->find($idr);
    
        return $this->render('reclamations/details.html.twig', [
            'reclamation' => $reclamation,
        ]);
    }

    #[Route('/{idr}/edit', name: 'app_reclamations_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Reclamations $reclamation, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ReclamationsType::class, $reclamation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_reclamations_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('reclamations/edit.html.twig', [
            'reclamation' => $reclamation,
            'form' => $form,
        ]);
    }

    #[Route('/{idr}', name: 'app_reclamations_delete', methods: ['POST'])]
    public function delete(Request $request, Reclamations $reclamation, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$reclamation->getIdr(), $request->request->get('_token'))) {
            $entityManager->remove($reclamation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_reclamations_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/reclamations/search', name: 'app_reclamations_search')]
public function searchPage(): Response
{
    return $this->render('reclamations/search.html.twig');
}

#[Route('/search', name: 'ajax_search', methods: ['GET'])]
public function search(Request $request, ReclamationRepository $reclamationRepository): JsonResponse
{
    $searchString = $request->query->get('q');
    $reclamations = $reclamationRepository->findReclamationsByString($searchString);

    $reclamationDetails = [];
    foreach ($reclamations as $reclamation) {
        $reclamationDetails[] = [
            'idr' => $reclamation->getIdr(),
            'details' => $reclamation->getDetails(),
            // Ajoutez d'autres détails de réclamation si nécessaires
        ];
    }

    return new JsonResponse(['reclamations' => $reclamationDetails]);
}


}
