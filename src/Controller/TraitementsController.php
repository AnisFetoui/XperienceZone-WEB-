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
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\PieChart;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\Histogram;

#[Route('/traitements')]
class TraitementsController extends AbstractController
{
    #[Route('/', name: 'app_traitements_index', methods: ['GET'])]
    public function index(TraitementRepository $traitementRepository, Request $request,PaginatorInterface $paginator): Response
    {   
        $criteria = $request->query->get('criteria', 'idt'); 
        
        
        $validCriteria = ['idt', 'refobj', 'dater', 'typer', 'resume', 'stat']; 
        
        if (!in_array($criteria, $validCriteria)) {
            $criteria = 'idt';
        }

        $traitement = $traitementRepository->findBy([], [$criteria => 'ASC']);
        $query = $traitement;
    
            $itemsPerPage = 5;
            $pagination = $paginator->paginate(
                $query,
                $request->query->getInt('page', 1),
                $itemsPerPage
            );

        return $this->render('traitements/index.html.twig', [
            'traitements' => $pagination,
        ]);
    }

    #[Route('/details/{idr}', name: 'app_traitements_details', methods: ['GET'])]
    public function details(string $idr, TraitementRepository $traitementRepository): Response
    {
         $traitement = $traitementRepository->find($idr);
    
        return $this->render('traitements/details.html.twig', [
            'traitement' => $traitement,
        ]);
    }


    #[Route('/trait/search', name: 'app_traitements_search')]
    public function searchPage(): Response
    {
        return $this->render('traitements/search.html.twig');
    }
    

    #[Route('/search', name: 'ajax_search', methods: ['GET'])]
public function search(Request $request, TraitementRepository $traitementRepository): JsonResponse
{
    $searchString = $request->query->get('q');
    $traitements = $traitementRepository->findReclamationsByString($searchString);

    $reclamationDetails = [];
    foreach ($traitements as $traitement) {
        $reclamationDetails[] = [
            'idt' => $traitement->getIdt(),
            'details' => $traitement->getResume(),
            // Ajoutez d'autres détails de réclamation si nécessaires
        ];
    }

    return new JsonResponse(['reclamations' => $reclamationDetails]);
}



#[Route('/stats', name: 'app_traitements_stats', methods: ['GET'])]
public function traitementStats(TraitementRepository $traitementRepository): Response
{
    $validCount = $traitementRepository->countByStat('Valide');
    $invalidCount = $traitementRepository->countByStat('Invalide');

    $pieChart = new PieChart();
    $pieChart->getData()->setArrayToDataTable([
        ['Statut', 'Nombre'],
        ['Valide', $validCount],
        ['Invalide', $invalidCount],
    ]);
    $pieChart->getOptions()->setTitle('Répartition des traitements par statut');
    $pieChart->getOptions()->setHeight(400);
    $pieChart->getOptions()->setWidth(600);

    $validCounts = $traitementRepository->countByTypeAndStat('Valide');
$invalidCounts = $traitementRepository->countByTypeAndStat('Invalide');

// Création d'un tableau pour regrouper les résultats par type
$groupedData = [];

// Remplissage du tableau avec les données de traitement valides pour chaque type
foreach ($validCounts as $validCount) {
    $type = $validCount['typer'];
    $groupedData[$type]['valid'] = (int) $validCount['count'];
}

// Ajout des données de traitement invalide pour chaque type dans le tableau
foreach ($invalidCounts as $invalidCount) {
    $type = $invalidCount['typer'];
    $groupedData[$type]['invalid'] = (int) $invalidCount['count'];
}

// Création du tableau final pour le graphique en histogramme
$data = [['Type', 'Traitements Validés', 'Traitements Invalides']];

foreach ($groupedData as $type => $stats) {
    $data[] = [
        'Type ' . $type,
        isset($stats['valid']) ? $stats['valid'] : 0,
        isset($stats['invalid']) ? $stats['invalid'] : 0,
    ];
}

// Création de l'objet Histogram avec les données ajustées
$histogram = new Histogram();
$histogram->getData()->setArrayToDataTable($data);
$histogram->getOptions()->setTitle('Répartition des traitements par type et statut');
$histogram->getOptions()->setWidth(900);
$histogram->getOptions()->setHeight(500);


    return $this->render('traitements/chart.html.twig', [
        'pieChart' => $pieChart,
        'histogram' => $histogram,
    ]);
}

#[Route('/generateExcel', name: 'excelt')]
public function generateExcel(TraitementRepository $evepo): BinaryFileResponse
{
    $reclamation = $evepo->findAll();
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    $sheet->setCellValue('A1', 'refobj');
    $sheet->setCellValue('B1', 'dater');
    $sheet->setCellValue('C1', 'idu');
    $sheet->setCellValue('D1', 'typer');
    $sheet->setCellValue('E1', 'resume');
    $sheet->setCellValue('F1', 'stat');

    $sn = 1;
    foreach ($reclamation as $p) {
        $sheet->setCellValue('A' . $sn, $p->getRefobj());
        $sheet->setCellValue('B' . $sn, $p->getDater());
        $sheet->setCellValue('C' . $sn, $p->getIdu());
        $sheet->setCellValue('D' . $sn, $p->getTyper());
        $sheet->setCellValue('E' . $sn, $p->getResume());
        $sheet->setCellValue('F' . $sn, $p->getStat());

        $sn++;
    }

    $writer = new Xlsx($spreadsheet);

    $fileName = 'traitements.xlsx';
    $temp_file = tempnam(sys_get_temp_dir(), $fileName);

    $writer->save($temp_file);

    return new BinaryFileResponse($temp_file, 200, [
        'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'Content-Disposition' => sprintf('inline; filename="%s"', $fileName),
    ]);
}


    #[Route('/new', name: 'app_traitements_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $traitement = new Traitements();
        $form = $this->createForm(TraitementsType::class, $traitement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $formData = $form->getData();

        // Récupérer la réclamation sélectionnée dans le formulaire
        $reclamation = $formData->getReclamations();

        if ($reclamation) {
            // Utiliser les attributs de la réclamation pour remplir les champs de l'entité Traitements
            $traitement->setDater($reclamation->getDaterec());
            $traitement->setTyper($reclamation->getTyperec());
            $traitement->setRefobj($reclamation->getRefobject());
            $traitement->setIdU($reclamation->getUtilisateur()->getIdUser());
            $entityManager->persist($traitement);
            $entityManager->flush();

            return $this->redirectToRoute('app_traitements_index', [], Response::HTTP_SEE_OTHER);
        }}

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
