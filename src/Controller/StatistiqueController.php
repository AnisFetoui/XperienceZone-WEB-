<?php

namespace App\Controller;

use App\Repository\UserrRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class StatistiqueController extends AbstractController
{
    #[Route('/stat', name: 'app_statistique')]
    public function index(): Response
    {
        return $this->render('statistique/index.html.twig', [
            'controller_name' => 'StatistiqueController',
        ]);
    }


    
    #[Route('/statistique', name: 'app_statistique')]
    public function statistique(UserrRepository $userRepository): Response
    {
        $totalUtilisateurs = $userRepository->count([]);
        $hommes = $userRepository->countByGenre('Male');
        $femmes = $userRepository->countByGenre('Female');
        $Age20 = $userRepository->countByAgeRange([0,20]);
        $Age40 = $userRepository->countByAgeRange([20,40]);
        $Age60 = $userRepository->countByAgeRange([40,120]);
        
        $pourcentageAge20 = ($Age20 / $totalUtilisateurs) * 100;
        $pourcentageAge40 = ($Age40 / $totalUtilisateurs) * 100;
        $pourcentageAge60 = ($Age60 / $totalUtilisateurs) * 100;


        $pourcentageHommes = ($hommes / $totalUtilisateurs) * 100;
        $pourcentageFemmes = ($femmes / $totalUtilisateurs) * 100;

        return $this->render('statistique/sexe.html.twig', [
            'Age20' => $Age20,
            'Age40' => $Age40,
            'Age60' => $Age60,
            'pourcentageAge60' => $pourcentageAge60,
            'pourcentageAge40' => $pourcentageAge40,
            'pourcentageAge20' => $pourcentageAge20,
            'totalUtilisateurs' => $totalUtilisateurs,
            'hommes' => $hommes,
            'femmes' => $femmes,
            'pourcentageHommes' => $pourcentageHommes,
            'pourcentageFemmes' => $pourcentageFemmes,
        ]);
        
    }
}
