<?php

namespace App\Controller;

use App\Entity\Traitements;
use App\Form\TraitementsType;
use App\Repository\TraitementRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/traitements')]
class TraitementsController extends AbstractController
{
    #[Route('/', name: 'app_traitements_index', methods: ['GET'])]
    public function index(TraitementRepository $traitementRepository): Response
    {
        return $this->render('traitements/index.html.twig', [
            'traitements' => $traitementRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_traitements_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $traitement = new Traitements();
        $form = $this->createForm(TraitementsType::class, $traitement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($traitement);
            $entityManager->flush();

            return $this->redirectToRoute('app_traitements_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('traitements/new.html.twig', [
            'traitement' => $traitement,
            'form' => $form,
        ]);
    }

    #[Route('/{idt}', name: 'app_traitements_show', methods: ['GET'])]
    public function show(Traitements $traitement): Response
    {
        return $this->render('traitements/show.html.twig', [
            'traitement' => $traitement,
        ]);
    }

    #[Route('/{idt}/edit', name: 'app_traitements_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Traitements $traitement, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TraitementsType::class, $traitement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_traitements_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('traitements/edit.html.twig', [
            'traitement' => $traitement,
            'form' => $form,
        ]);
    }

    #[Route('/{idt}', name: 'app_traitements_delete', methods: ['POST'])]
    public function delete(Request $request, Traitements $traitement, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$traitement->getIdt(), $request->request->get('_token'))) {
            $entityManager->remove($traitement);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_traitements_index', [], Response::HTTP_SEE_OTHER);
    }
}
