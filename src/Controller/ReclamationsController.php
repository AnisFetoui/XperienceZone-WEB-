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
use Symfony\Component\Security\Core\User\UserInterface;

use Symfony\Component\HttpFoundation\JsonResponse;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\PieChart;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\Histogram;
use Swift_Mailer;
use Swift_Message;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Knp\Component\Pager\PaginatorInterface;



#[Route('/reclamations')]
class ReclamationsController extends AbstractController
{/*
    #[Route('/nacer', name: 'app_reclamations_index', methods: ['GET'])]
    public function index(ReclamationRepository $reclamationRepository, Request $request, PaginatorInterface $paginator): Response
    {
        $criteria = $request->query->get('criteria', 'idr'); 
        
        
        $validCriteria = ['idr', 'daterec', 'typerec', 'refobject', 'details']; 
        
        if (!in_array($criteria, $validCriteria)) {
            $criteria = 'idr';
        }

        $reclamations = $reclamationRepository->findBy([], [$criteria => 'ASC']);
        $query = $reclamations;
    
            $itemsPerPage = 5;
            $pagination = $paginator->paginate(
                $query,
                $request->query->getInt('page', 1),
                $itemsPerPage
            );
    

        return $this->render('reclamations/index.html.twig', [
            'reclamations' => $pagination,
        ]);
        
    }*/

    #[Route('/new', name: 'app_reclamations_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, Swift_Mailer $mailer,UserInterface $user): Response
    {
        $reclamation = new Reclamations();
        $form = $this->createForm(ReclamationsType::class, $reclamation);
        $form->handleRequest($request);
        $userId = $user->getIdUser();

        if ($form->isSubmitted() && $form->isValid()) {
            $reclamation->setUtilisateur($user);
            $entityManager->persist($reclamation);
            $entityManager->flush();

            return $this->redirectToRoute('app_reclamations_new', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('reclamations/new.html.twig', [
            'reclamation' => $reclamation,
            'form' => $form,
            'iduser'=>$userId,
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

#[Route('/search', name: 'ajax_search1', methods: ['GET'])]
public function search(Request $request, ReclamationRepository $reclamationRepository): JsonResponse
{
    $searchString = $request->query->get('q');
    $reclamations = $reclamationRepository->findReclamationsByString($searchString);

    $reclamationDetails = [];
    foreach ($reclamations as $reclamation) {
        $reclamationDetails[] = [
            'idr' => $reclamation->getIdr(),
            'details' => $reclamation->getDetails(),
           
        ];
    }

    return new JsonResponse(['reclamations' => $reclamationDetails]);
}



#[Route('/chart', name: 'app_reclamations_stat', methods: ['GET'])]
public function indexAction(ReclamationRepository $reclamationRepository)
{
    // Récupération des statistiques des réclamations par rapport au type de réclamation pour le Pie Chart
    $reclamationsByType = $reclamationRepository->getCountByType();

$pieChart = new PieChart();
$dataForPieChart = [
    ['Type de réclamation', 'Nombre']
];


$typeLabels = [
    1 => 'Produit',
    2 => 'Événement',
    3 => 'Activité'
];

foreach ($reclamationsByType as $reclamation) {
    $typeLabel = $typeLabels[$reclamation['typerec']] ?? 'Inconnu'; // Utiliser 'Inconnu' si le type n'est pas trouvé
    $dataForPieChart[] = [$typeLabel, $reclamation['count']];
}

$pieChart->getData()->setArrayToDataTable($dataForPieChart);
$pieChart->getOptions()->setTitle('Statistiques des réclamations par type');

    
   

    $reclamationsByTypeAndMonth = $reclamationRepository->getCountByTypeAndMonth();

    $histogram = new Histogram();
    $dataForHistogram = [
        ['Type de réclamation', 'Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre']
    ];

   
    $countsByType = [
        'produit' => array_fill(0, 12, 0),
        'evenement' => array_fill(0, 12, 0),
        'activite' => array_fill(0, 12, 0)
    ];

    foreach ($reclamationsByTypeAndMonth as $reclamation) {
        $typeLabel = '';

        switch ($reclamation['typerec']) {
            case 1:
                $typeLabel = 'produit';
                break;
            case 2:
                $typeLabel = 'evenement';
                break;
            case 3:
                $typeLabel = 'activite';
                break;
            default:
                $typeLabel = 'inconnu';
        }

        $monthIndex = intval($reclamation['mois']) - 1;

        $countsByType[$typeLabel][$monthIndex]++;
    }

    foreach ($countsByType as $type => $counts) {
        $dataRow = [$type];
        foreach ($counts as $count) {
            $dataRow[] = $count;
        }
        $dataForHistogram[] = $dataRow;
    }

    $histogram->getData()->setArrayToDataTable($dataForHistogram);
    $histogram->getOptions()->setTitle('Statistiques des réclamations par type et mois (2023)');

    return $this->render('reclamations/chart.html.twig', [
        'pieChart' => $pieChart,
        'histogram' => $histogram
    ]);
}

#[Route('/generateExcel', name: 'excel')]
public function generateExcel(ReclamationRepository $evepo): BinaryFileResponse
{
    $reclamation = $evepo->findAll();
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    $sheet->setCellValue('A1', 'Date');
    $sheet->setCellValue('B1', 'Type');
    $sheet->setCellValue('C1', 'Reference');
    $sheet->setCellValue('D1', 'details');

    $sn = 1;
    foreach ($reclamation as $p) {
        $sheet->setCellValue('A' . $sn, $p->getDaterec());
        $sheet->setCellValue('B' . $sn, $p->getTyperec());
        $sheet->setCellValue('C' . $sn, $p->getRefobject());
        $sheet->setCellValue('D' . $sn, $p->getDetails());

        $sn++;
    }

    $writer = new Xlsx($spreadsheet);

    $fileName = 'reclamations.xlsx';
    $temp_file = tempnam(sys_get_temp_dir(), $fileName);

    $writer->save($temp_file);

    return new BinaryFileResponse($temp_file, 200, [
        'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'Content-Disposition' => sprintf('inline; filename="%s"', $fileName),
    ]);
}


}
