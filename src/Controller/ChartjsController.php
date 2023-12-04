<?php

namespace App\Controller;

use App\Repository\EvenementRepository;
use App\Repository\MessageRepository;
use App\Repository\ChannelRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;
use Doctrine\ORM\EntityRepository;

class ChartjsController extends AbstractController 
{
    #[Route('/chartjs', name: 'app_chartjs')]
    public function index(ChartBuilderInterface $chartBuilder, ChannelRepository $channelRepository, EvenementRepository $evenementRepository, messageRepository $messagee): Response
    {   
        $msgs = $messageRepository->findAll();
        $labels = [];
        $data = [];
       
        foreach ($msgs as $msg) {
            $labels[] = $msg->getHeurEnvoiMsg()->format('Y-m-d');
            $data[] = strlen($msg->getContenuMsg());
        }
  

        $chart = $chartBuilder->createChart(Chart::TYPE_BAR);
    
        $chart->setData([
            'labels' => $labels,
           
            'datasets' => [
                [
                    'label' => 'Message Content Length',
                    'backgroundColor' => 'rgb(255, 99, 132)',
                    'borderColor' => 'rgb(255, 99, 132)',
                    'data' => $data,
                ],
            ],
        ]);
    
        return $this->render('chartjs/index.html.twig', [
            'controller_name' => 'ChartjsController',
            'chart' => $chart,
        ]);
  
    
}}

  
