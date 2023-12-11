<?php

namespace App\Controller;

use App\Entity\Panier;
use App\Form\PanierType;
use App\Repository\PanierRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
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

   /* * #[Route('/new', name: 'app_panier_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, FlashyNotifier $flashy,UserRepository $userRepository ): Response
    {
        $panier = new Panier();

        $form = $this->createForm(PanierType::class, $panier);
        $form->handleRequest($request);
        $currentUser = $this->getUser(); // Get the authenticated user

    
        if ($form->isSubmitted() && $form->isValid()) {
            $produit = $panier->getProduit();
            
    
           
            if ($produit->getQuantite() < $panier->getQuantitePanier()) {
                $this->addFlash('mercuryseries_flashy_notification', 'the quantity is out of stock.');
                return $this->redirectToRoute('app_panier_index', [], Response::HTTP_SEE_OTHER);
            }
    
           
            $prixProduit = $produit->getPrixProd();
            $quantitePanier = $panier->getQuantitePanier();
            $total = $prixProduit * $quantitePanier;
    
            
            if ($total > 100) {
                $remise = $total * 0.10; 
                $totalAvecRemise = $total - $remise;
                $panier->setTotal($totalAvecRemise);
                $this->addFlash('mercuryseries_flashy_notification', 'You have a discount of 10% !');
            } else {
                $panier->setTotal($total);
            }
    
            $nouvelleQuantite = $produit->getQuantite() - $quantitePanier;
            $produit->setQuantite($nouvelleQuantite);
            if ($currentUser instanceof UserInterface) {
                $panier->setUtilisateur($currentUser);
            } else {
                // Handle the case where the user is not authenticated
                $this->addFlash('mercuryseries_flashy_notification', 'User not authenticated.');
                return $this->redirectToRoute('app_login'); // Redirect to login page or handle it in another way
            }
    
            $entityManager->persist($panier);
            $entityManager->persist($produit); 
            $entityManager->flush();
            
            $this->addFlash('mercuryseries_flashy_notification', 'Product Added successfully :)');
    
            return $this->redirectToRoute('app_panier_index', [], Response::HTTP_SEE_OTHER);
        }
    
        return $this->renderForm('panier/new.html.twig', [
            'panier' => $panier,
            'form' => $form
        ]);
    }*/

    #[Route('/new', name: 'app_panier_new', methods: ['GET', 'POST'])]
public function new(Request $request, EntityManagerInterface $entityManager, FlashyNotifier $flashy, UserRepository $userRepository, UserInterface $user): Response
{
    $panier = new Panier();

    $form = $this->createForm(PanierType::class, $panier);
    $form->handleRequest($request);


    


    if ($form->isSubmitted() && $form->isValid()) {
        $produit = $panier->getProduit();
        $panier->setUtilisateur($user);

        if ($produit->getQuantite() < $panier->getQuantitePanier()) {
            $this->addFlash('mercuryseries_flashy_notification', 'The quantity is out of stock.');
            return $this->redirectToRoute('app_panier_index', [], Response::HTTP_SEE_OTHER);
        }

        $prixProduit = $produit->getPrixProd();
        $quantitePanier = $panier->getQuantitePanier();
        $total = $prixProduit * $quantitePanier;

        if ($total > 100) {
            $remise = $total * 0.10;
            $totalAvecRemise = $total - $remise;
            $panier->setTotal($totalAvecRemise);
            $this->addFlash('mercuryseries_flashy_notification', 'You have a discount of 10%!');
        } else {
            $panier->setTotal($total);
        }

        $nouvelleQuantite = $produit->getQuantite() - $quantitePanier;
        $produit->setQuantite($nouvelleQuantite);

        // Set the user on the Panier entity


        $entityManager->persist($panier);
        $entityManager->persist($produit);
        $entityManager->flush();

        $this->addFlash('mercuryseries_flashy_notification', 'Product Added successfully :)');

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
    

