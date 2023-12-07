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
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Font;



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
            5 
        );
    
        return $this->render('produit/index.html.twig', [
            'produits' => $produits,
            'nomProd' => $nomProd,
            'prixProd' => $prixProd,
            'panier_form' => $this->createFormBuilder()->getForm()->createView(), 

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
    


    #[Route('/back', name: 'produitback_index', methods: ['GET'])]
public function backofficeprod(Request $request,ProduitRepository $produitRepository,PaginatorInterface $paginator): Response
{
    $criteria = $request->query->get('criteria', 'idProd'); 
        
        
        $validCriteria = ['idProd', 'nomProd', 'DescriptionProd', ]; 
        
        if (!in_array($criteria, $validCriteria)) {
            $criteria = 'idProd';
        }

        $produits = $produitRepository->findBy([], [$criteria => 'ASC']);
        $produitsQuery=$produits;
        //$produitsQuery = $produitRepository->createQueryBuilder('p')->getQuery();
    
        $produits = $paginator->paginate(
            $produitsQuery,
            $request->query->getInt('page', 1),
            5 
        );
    
        if ($request->query->get('excel')) {
            
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
    
            $sheet->setCellValue('A1', 'ID')->getStyle('A1')->applyFromArray([
                'font' => ['bold' => true],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'FFCC00']],
            ]);
            $sheet->setCellValue('B1', 'Nom')->getStyle('B1')->applyFromArray(['font' => ['bold' => true]]);
            $sheet->setCellValue('C1', 'Prix')->getStyle('C1')->applyFromArray(['font' => ['bold' => true]]);
            $sheet->setCellValue('D1', 'Description')->getStyle('D1')->applyFromArray(['font' => ['bold' => true]]);
            $sheet->setCellValue('E1', 'Quantité')->getStyle('E1')->applyFromArray(['font' => ['bold' => true]]);
            $sheet->setCellValue('F1', 'Catégorie')->getStyle('F1')->applyFromArray(['font' => ['bold' => true]]);
            $sheet->setCellValue('G1', 'Note')->getStyle('G1')->applyFromArray(['font' => ['bold' => true]]);
    
            $row = 2;
            foreach ($produits as $produit) {
                $sheet->setCellValue('A' . $row, $produit->getIdProd());
                $sheet->setCellValue('B' . $row, $produit->getNomProd());
                $sheet->setCellValue('C' . $row, $produit->getPrixProd());
                $sheet->setCellValue('D' . $row, $produit->getDescriptionProd());
                $sheet->setCellValue('E' . $row, $produit->getQuantite());
                $sheet->setCellValue('F' . $row, $produit->getCategorie()->getNomCategorie()); 
                $sheet->setCellValue('G' . $row, $produit->getNoteProd());
    
                $style = $row % 2 == 0 ? ['fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'F2F2F2']]] : [];
                $sheet->getStyle('A' . $row . ':G' . $row)->applyFromArray($style);
    
                $row++;
            }
    
            foreach (range('A', 'G') as $col) {
                $sheet->getColumnDimension($col)->setAutoSize(true);
            }
    
            
            $writer = new Xlsx($spreadsheet);
            $excelFilePath = tempnam(sys_get_temp_dir(), 'produits_export') . '.xlsx';
            $writer->save($excelFilePath);
    
            
            $response = new Response(file_get_contents($excelFilePath));
            $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            $response->headers->set('Content-Disposition', 'attachment;filename="produits_export.xlsx"');
            $response->headers->set('Cache-Control', 'max-age=0');
            $response->headers->set('Pragma', 'public');
    
            unlink($excelFilePath); 
    
            return $response;
        }
    
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

        return $this->redirectToRoute('produitback_index', [], Response::HTTP_SEE_OTHER);
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

            return $this->redirectToRoute('produitback_index', [], Response::HTTP_SEE_OTHER);
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

        return $this->redirectToRoute('produitback_index', [], Response::HTTP_SEE_OTHER);
    }
   
   
    #[Route('/produit/{idProd}/rate/{note}', name: 'rate_product', methods: ['GET'])]
public function rateProduct(Produit $produit, int $note, EntityManagerInterface $entityManager): RedirectResponse
{
    if ($note < 1 || $note > 5) {
        $this->addFlash('error', 'La note doit être comprise entre 1 et 5.');
    } else {
        $produit->setNoteProd($note);

        $entityManager->flush();

        $this->addFlash('success', 'La note a été enregistrée avec succès.');
    }

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
   
}

    


    
    
