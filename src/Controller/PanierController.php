<?php

namespace App\Controller;

use App\Entity\Panier;
use App\Form\PanierType;
use App\Repository\PanierRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\FormEvents;
use MercurySeries\FlashyBundle\FlashyNotifier;



#[Route('/panier')]
class PanierController extends AbstractController
{
    #[Route('/', name: 'app_panier_index', methods: ['GET'])]
    public function index(PanierRepository $panierRepository): Response
    {
        return $this->render('panier/index.html.twig', [
            'paniers' => $panierRepository->findAll(),
        ]);
    } 

    #[Route('/new', name: 'app_panier_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, FlashyNotifier $flashy): Response
    {
        $panier = new Panier();
        $form = $this->createForm(PanierType::class, $panier);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $produit = $panier->getProduit();
    
            // Vérifier si la quantité demandée est disponible en stock
            if ($produit->getQuantite() < $panier->getQuantitePanier()) {
                $this->addFlash('mercuryseries_flashy_notification', 'La quantité demandée n\'est pas disponible en stock.');
                return $this->redirectToRoute('app_panier_index', [], Response::HTTP_SEE_OTHER);
            }
    
            // Continuer avec le reste du traitement si la quantité est disponible
            $prixProduit = $produit->getPrixProd();
            $quantitePanier = $panier->getQuantitePanier();
            $total = $prixProduit * $quantitePanier;
    
            // Appliquer une remise de 10% si le total est supérieur à 100
            if ($total > 100) {
                $remise = $total * 0.10; // 10% de remise
                $totalAvecRemise = $total - $remise;
                $panier->setTotal($totalAvecRemise);
                $this->addFlash('mercuryseries_flashy_notification', 'Vous avez reçu une remise de 10% !');
            } else {
                $panier->setTotal($total);
            }
    
            // Mettre à jour le stock du produit
            $nouvelleQuantite = $produit->getQuantite() - $quantitePanier;
            $produit->setQuantite($nouvelleQuantite);
    
            $entityManager->persist($panier);
            $entityManager->persist($produit); // Mettre à jour le stock du produit
            $entityManager->flush();
            
            $this->addFlash('mercuryseries_flashy_notification', 'Produit ajouté avec succès :)');
    
            return $this->redirectToRoute('app_panier_index', [], Response::HTTP_SEE_OTHER);
        }
    
        return $this->renderForm('panier/new.html.twig', [
            'panier' => $panier,
            'form' => $form
        ]);
    }

    #[Route('/{idPanier}', name: 'app_panier_show', methods: ['GET'])]
    public function show(Panier $panier): Response
    {
        return $this->render('panier/show.html.twig', [
            'panier' => $panier,
        ]);
    }

    #[Route('/{idPanier}/edit', name: 'app_panier_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Panier $panier, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PanierType::class, $panier);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            if ($panier->getQuantitePanier() <= 0) {
                $this->addFlash('mercuryseries_flashy_notification', 'Quantity must be greater than zero.');
                return $this->redirectToRoute('app_panier_index', [], Response::HTTP_SEE_OTHER);
            }
            // ...
    
            // Ajuster le total en fonction de la quantité et du prix du produit
            $produit = $panier->getProduit();
            $prixProduit = $produit->getPrixProd();
            $quantitePanier = $panier->getQuantitePanier();
            $panier->setTotal($prixProduit * $quantitePanier);
    
            $entityManager->flush();
            $this->addFlash('mercuryseries_flashy_notification', 'Product updated successfully :)');

            return $this->redirectToRoute('app_panier_index', [], Response::HTTP_SEE_OTHER);
        }
    
        return $this->render('panier/edit.html.twig', [
            'panier' => $panier,
            'form' => $form->createView(),
        ]);
    }

    
    
    

    #[Route('/{idPanier}', name: 'app_panier_delete', methods: ['POST'])]
    public function delete(Request $request, Panier $panier, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$panier->getIdPanier(), $request->request->get('_token'))) {
            $entityManager->remove($panier);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_panier_index', [], Response::HTTP_SEE_OTHER);
    }
   
   // ...

public function indexx(PanierRepository $panierRepository, EntityManagerInterface $entityManager): Response
{
    $panierItems = $panierRepository->findAll();

    $totalPanier = 0.0;

    foreach ($panierItems as $item) {
        $produit = $item->getProduit();

        if ($produit instanceof Produit) {
            $prixProduit = $produit->getPrixProd();
            $quantitePanier = $item->getQuantite();
            $totalPanier += $prixProduit * $quantitePanier;
        }
    }

    return $this->render('panier/index.html.twig', [
        'panierItems' => $panierItems,
        'totalPanier' => $totalPanier,
    ]);
}




}
    

