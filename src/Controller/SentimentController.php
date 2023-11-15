<?php

namespace App\Controller;

use App\Entity\Sentiment;
use App\Form\SentimentType;
use App\Repository\SentimentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/sentiment')]
class SentimentController extends AbstractController
{
    #[Route('/', name: 'app_sentiment_index', methods: ['GET'])]
    public function index(SentimentRepository $sentimentRepository): Response
    {
        return $this->render('sentiment/index.html.twig', [
            'sentiments' => $sentimentRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_sentiment_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $sentiment = new Sentiment();
        $form = $this->createForm(SentimentType::class, $sentiment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($sentiment);
            $entityManager->flush();

            return $this->redirectToRoute('app_sentiment_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('sentiment/new.html.twig', [
            'sentiment' => $sentiment,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_sentiment_show', methods: ['GET'])]
    public function show(Sentiment $sentiment): Response
    {
        return $this->render('sentiment/show.html.twig', [
            'sentiment' => $sentiment,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_sentiment_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Sentiment $sentiment, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(SentimentType::class, $sentiment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_sentiment_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('sentiment/edit.html.twig', [
            'sentiment' => $sentiment,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_sentiment_delete', methods: ['POST'])]
    public function delete(Request $request, Sentiment $sentiment, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$sentiment->getId(), $request->request->get('_token'))) {
            $entityManager->remove($sentiment);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_sentiment_index', [], Response::HTTP_SEE_OTHER);
    }
}
