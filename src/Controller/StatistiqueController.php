<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\EvenementRepository;

class StatistiqueController extends AbstractController
{
    #[Route('/statistique', name: 'app_statistique')]
    public function index(): Response
    {
        return $this->render('statistique/index.html.twig', [
            'controller_name' => 'StatistiqueController',
        ]);
    }

    /*#[Route('/statistique/lieu', name: 'app_statistique_lieu')]
    public function statistiqueLieu(EvenementRepository $evenementRepository): Response
    {
        $statsLieu = $evenementRepository->countEventsByLocation();

        return $this->render('statistique/lieu.html.twig', [
            'statsLieu' => $statsLieu,
        ]);
    }
*/

/*#[Route('/statistiques', name: 'statistiques')]
public function indexs(): Response
{
    $repository = $this->getDoctrine()->getRepository(Evenement::class);
    $evenements = $repository->findAll();
    
    $counts = Evenement::countEventsByLocation($evenements);
    
    return $this->render('statistique/lieu.html.twig', [
        'counts' => $counts,
    ]);
}*/
#[Route('/statistiques', name: 'statistiques')]
public function indexx(EvenementRepository $evenementRepository): Response
{
    $statsLieu = $evenementRepository->countEventsByLocation();

    return $this->render('statistique/lieu.html.twig', [
        'statsLieu' => $statsLieu,
    ]);
}





}






