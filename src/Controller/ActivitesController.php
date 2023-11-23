<?php

namespace App\Controller;

use App\Entity\Activites;
use App\Form\ActivitesType;
use App\Entity\Inscription;
use App\Form\InscriptionType;
use App\Repository\InscriptionRepository;
use App\Repository\ActivitesRepository;
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

    #[Route('/name', name: 'app_activites_name', methods: ['GET'])]
    public function namefind(Request $request,ActivitesRepository $activitesRepository): Response
    {
        /*$name = "padle";*/
        $name = $request->query->get('activityName');
        $act = $activitesRepository->findBy(['nomAct' => $name]);

    return $this->render('activites/index.html.twig', ['activites' => $act]);
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



    #[Route('/{idAct}', name: 'app_activites_show', methods: ['GET', 'POST'])]
    public function show(Activites $activite , Request $request, EntityManagerInterface $entityManager,$idAct): Response
    {
        $inscription = new Inscription();
        $inscription->setNbrTickes(1);
        $prix = $activite->getPrixAct();
        $form = $this->createForm(InscriptionType::class, $inscription);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $inscription -> setActiviteId($idAct);
            $inscription -> setUserId(0);
            $inscription -> setFraitAbonnement(20.00);
            dump($inscription);
            $entityManager->persist($inscription);
            $entityManager->flush();

            return $this->redirectToRoute('app_activites_show', ['idAct' => $idAct], Response::HTTP_SEE_OTHER);

        }
        return $this->renderForm('activites/show.html.twig', [
            'activite' => $activite,
            'inscription' => $inscription,
            'form' => $form,
            'id'=>$idAct,
            'prixactivite'=>$prix,
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
