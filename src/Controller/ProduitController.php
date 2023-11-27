<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Form\ProduitType;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\String\Slugger\SluggerInterface;
use function Symfony\Component\String\u;
use Symfony\Component\HttpFoundation\JsonResponse;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;



#[Route('/produit')]
class ProduitController extends AbstractController

{

    #[Route('/', name: 'app_produit_index', methods: ['GET'])]
    public function index(Request $request, ProduitRepository $produitRepository, PaginatorInterface $paginator): Response
    {
        $nomProd = $request->query->get('nomProd');
        $prixProd = $request->query->get('prixProd');
    
        if ($nomProd || $prixProd) {
            $produitsQuery = $produitRepository->findProductByCriteria($nomProd, $prixProd);
        } else {
            $produitsQuery = $produitRepository->createQueryBuilder('p')->getQuery();
        }
    
        $produits = $paginator->paginate(
            $produitsQuery,
            $request->query->getInt('page', 1),
            5 // Nombre d'articles par page
        );
    
        return $this->render('produit/index.html.twig', [
            'produits' => $produits,
            'nomProd' => $nomProd,
            'prixProd' => $prixProd,
        ]);
    }

    #[Route('/back', name: 'produitback_index', methods: ['GET'])]
    public function backofficeact(Request $request,ProduitRepository $produitRepository,PaginatorInterface $paginator): Response
    {
        $produitsQuery = $produitRepository->createQueryBuilder('p')->getQuery();

        $produits = $paginator->paginate(
            $produitsQuery,
            $request->query->getInt('page', 1),
            5 // Nombre d'articles par page
        );

        return $this->render('produit/indexadmin.html.twig', [
            'produits' => $produits,
        ]);
    
    }

    #[Route('/back/search', name: 'app_produit_search')]
    public function searchPage(): Response
    {
        return $this->render('produit/search.html.twig');
    }

   
    #[Route('/new', name: 'app_produit_new', methods: ['GET', 'POST'])]
public function new(Request $request, EntityManagerInterface $entityManager): Response
{
    $produit = new Produit();
    $form = $this->createForm(ProduitType::class, $produit);
    $form->handleRequest($request);

    // Spécifiez directement le chemin du répertoire d'images
    $imageDirectory = $this->getParameter('kernel.project_dir').'/public/uploads/images';

    if ($form->isSubmitted() && $form->isValid()) {
        $imageFile = $form->get('image')->getData();
        if ($imageFile) {
            $newFilename = uniqid().'.'.$imageFile->guessExtension();
            $imageFile->move($imageDirectory, $newFilename);

            $produit->setImage($newFilename);
        }

        $entityManager->persist($produit);
        $entityManager->flush();

        return $this->redirectToRoute('app_produit_index', [], Response::HTTP_SEE_OTHER);
    }

    return $this->renderForm('produit/new.html.twig', [
        'produit' => $produit,
        'form' => $form,
    ]);
}

    #[Route('/{idProd}', name: 'app_produit_show', methods: ['GET'])]
    public function show(Produit $produit): Response
    {$isNoteProdDefined = $produit->getNoteProd() !== null;

        return $this->render('produit/show.html.twig', [
            'produit' => $produit,
            'isNoteProdDefined' => $isNoteProdDefined,
        ]);
    }

    #[Route('/{idProd}/edit', name: 'app_produit_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Produit $produit, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_produit_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('produit/edit.html.twig', [
            'produit' => $produit,
            'form' => $form,
        ]);
    }

    #[Route('/{idProd}', name: 'app_produit_delete', methods: ['POST'])]
    public function delete(Request $request, Produit $produit, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$produit->getIdProd(), $request->request->get('_token'))) {
            $entityManager->remove($produit);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_produit_index', [], Response::HTTP_SEE_OTHER);
    }
   
   
    #[Route('/produit/{idProd}/rate/{note}', name: 'rate_product', methods: ['GET'])]
public function rateProduct(Produit $produit, int $note, EntityManagerInterface $entityManager): RedirectResponse
{
    // Validez la note pour vous assurer qu'elle est dans une plage valide (par exemple, 1 à 5)
    if ($note < 1 || $note > 5) {
        $this->addFlash('error', 'La note doit être comprise entre 1 et 5.');
    } else {
        // Mettez à jour la propriété noteProd du produit
        $produit->setNoteProd($note);

        // Enregistrez les modifications dans la base de données
        $entityManager->flush();

        $this->addFlash('success', 'La note a été enregistrée avec succès.');
    }

    // Redirigez l'utilisateur vers la page du produit
    return $this->redirectToRoute('app_produit_show', ['idProd' => $produit->getIdProd()]);
}

#[Route('/details/{idProd}', name: 'app_produits_details', methods: ['GET'])]
    public function details(string $idProd, ProduitRepository $produitrepository): Response
    {
         $produit = $produitrepository->find($idProd);
    
        return $this->render('produit/details.html.twig', [
            'produit' => $produit,
        ]);
    }
    #[Route('/search', name: 'ajax_search', methods: ['GET'])]
    public function search(Request $request, ProduitRepository $produitRepository): JsonResponse
    {    

        $searchString = $request->query->get('q');
        $produits = $produitRepository->findProduitByNom($searchString);
    
        $produitnom = [];
        foreach ($produits as $produit) {
            $produitnom[] = [
                'idProd' => $produit->getIdProd(),
                'nomProd' => $produit->getNomProd(),
            ];
        }
    
        return new JsonResponse(['produits' => $produitnom]);
    }
    

}

    


    
    
