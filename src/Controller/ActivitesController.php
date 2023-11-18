<?php

namespace App\Controller;

use App\Entity\Activites;
use App\Form\ActivitesType;
use App\Repository\ActivitesRepository;
use App\Repository\InscriptionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/activites')]
class ActivitesController extends AbstractController
{
    #[Route('/', name: 'app_activites_index', methods: ['GET'])]
    public function index(ActivitesRepository $activitesRepository): Response
    {
        return $this->render('activites/index.html.twig', [
            'activites' => $activitesRepository->findAll(),
        ]);
    }
    #[Route('/back', name: 'activitesback_index', methods: ['GET'])]
    public function backofficeact(ActivitesRepository $activitesRepository, InscriptionRepository $inscriptionRepository): Response
    {
        return $this->render('activites/backoffice.html.twig', [
            'activites' => $activitesRepository->findAll(),
            'inscriptions' => $inscriptionRepository->findAll(),
            
        ]);
    }
    #[Route('/abonner/{id}', name: 'abonner_activite', methods: ['GET'])]
    public function Abonner_Activite($id): Response
    {
        return $this->redirectToRoute('app_inscription_new', ['id' => $id]);
    }

    #[Route('/new', name: 'app_activites_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $activite = new Activites();
        $form = $this->createForm(ActivitesType::class, $activite);
        $form->handleRequest($request);
        $imagedirectory = $this->getParameter('kernel.project_dir').'/public/uploads/images';
        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('images')->getData();
            if($imageFile){
                $newFilename = uniqid().'.'.$imageFile->guessExtension();
                $imageFile->move($imagedirectory,$newFilename);
                $activite->setImages($newFilename);


            }
            $entityManager->persist($activite);
            $entityManager->flush();

            return $this->redirectToRoute('app_activites_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('activites/new.html.twig', [
            'activite' => $activite,
            'form' => $form,
        ]);
    }

    #[Route('/{idAct}', name: 'app_activites_show', methods: ['GET'])]
    public function show(Activites $activite): Response
    {
        return $this->render('activites/show.html.twig', [
            'activite' => $activite,
        ]);
    }

    #[Route('/{idAct}/edit', name: 'app_activites_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Activites $activite, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ActivitesType::class, $activite);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_activites_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('activites/edit.html.twig', [
            'activite' => $activite,
            'form' => $form,
        ]);
    }

    #[Route('/{idAct}', name: 'app_activites_delete', methods: ['POST'])]
    public function delete(Request $request, Activites $activite, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$activite->getIdAct(), $request->request->get('_token'))) {
            $entityManager->remove($activite);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_activites_index', [], Response::HTTP_SEE_OTHER);
    }
}
