<?php

namespace App\Controller;

use App\Entity\Channel;
use App\Form\ChannelType;
use App\Repository\ChannelRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Knp\Component\Pager\PaginatorInterface;


#[Route('/channels')]
class ChannelController extends AbstractController
{
  #[Route('/', name: 'app_channel_index', methods: ['GET'])]
  /*  public function index(ChannelRepository $channelRepository): Response
    {
        return $this->render('channel/index.html.twig', [
            'channels' => $channelRepository->findAll(),

        ]);*/
        public function index( PaginatorInterface $paginator, Request $request, ChannelRepository $channelRepository): Response
        {
        $allChannel =$channelRepository ->findAll();

        $channels = $paginator->paginate(
            $allChannel, 
            $request->query->getInt('page', 1), 
            5 //
        );
    
        return $this->render('channel/index.html.twig', [
            'channels' => $channels,
    
       ]);
    }
    
  /*
    #[Route('/new', name: 'app_channel_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $channel = new Channel();
        $form = $this->createForm(ChannelType::class, $channel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($channel);
            $entityManager->flush();

            return $this->redirectToRoute('app_channel_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('channel/new.html.twig', [
            'channel' => $channel,
            'form' => $form,
        ]);
    }
*/
    

    #[Route('/{idCh}', name: 'app_channel_show', methods: ['GET'])]
    public function show(Channel $channel): Response
    {
        return $this->render('channel/show.html.twig', [
            'channel' => $channel,
        ]);
    }

    #[Route('showchaima/{idCh}', name: 'app_channel_show', methods: ['GET'])]
    public function showchaima(Channel $channel): Response
    {
        return $this->render('channel/show.html.twig', [
            'channel' => $channel,
        ]);
    }


    #[Route('/back/{idCh}', name: 'app_channels_showback', methods: ['GET'])]
    public function showback(Channel $channel): Response
    {
        return $this->render('channel/showback.html.twig', [
            'channel' => $channel,
        ]);
    }

    #[Route('/{idCh}/edit', name: 'app_channel_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Channel $channel, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ChannelType::class, $channel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_channel_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('channel/edit.html.twig', [
            'channel' => $channel,
            'form' => $form,
        ]);
    }

  

  

    #[Route('/{idCh}', name: 'app_channel_delete', methods: ['POST'])]
    public function delete(Request $request, Channel $channel, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$channel->getIdCh(), $request->request->get('_token'))) {
            $entityManager->remove($channel);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_channel_index', [], Response::HTTP_SEE_OTHER);
    }
    
    #[Route('/channel/search', name: 'channel_searchx', methods: ['GET'])]
    public function search(Request $request, SerializerInterface $serializer, ChannelRepository $channelRepository): Response
    {
        $requestString = $request->get('search');
        $channels = $channelRepository->findChannelByNom($requestString);
        $data = [];
        foreach ($channels as $channel) {
            $data[] = [
                'idCh' =>$channel->getIdCh(),
                'nomCh' => $channel->getNomCh(),
                'evenement' => [
                    'nomEvent' => $channel->getEvenement() ? $channel->getEvenement()->getNomEvent() : null,
                    
                ],
            ];
        }
        $jsonContent = $serializer->serialize($data, 'json');

     return new Response($jsonContent, 200, ['Content-Type' => 'application/json']);

    }
 


    }

   
     
