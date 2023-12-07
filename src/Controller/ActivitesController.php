<?php

namespace App\Controller;
//use App\Controller\app;
use App\Entity\Activites;
use App\Form\ActivitesType;
use App\Entity\Inscription;
use App\Form\InscriptionType;
use App\Repository\InscriptionRepository;
use App\Repository\ActivitesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Symfony\Component\Security\Core\User\UserInterface;

use Endroid\QrCode\Label\Label;
use Endroid\QrCode\Logo\Logo;
use Endroid\QrCode\RoundBlockSizeMode;

use Endroid\QrCode\Writer\ValidationException;
use Swift_Mailer;
use Symfony\Component\Mailer\MailerInterface;

use Symfony\Component\Mime\Part\DataPart;
use Symfony\Component\Mime\Part\Multipart\AlternativePart;
use Symfony\Component\Mime\Part\Multipart\MixedPart;
use Symfony\Component\HttpFoundation\JsonResponse;



#[Route('/activites')]
class ActivitesController extends AbstractController
{
    #[Route('/', name: 'app_activites_index', methods: ['GET'])]
    public function index(ActivitesRepository $activitesRepository): Response
    {
        return $this->render('activites/index.html.twig', [
            'activites' => $activitesRepository->findAll(),
        ]);
    }


    #[Route('/tri/{criteria}', name: 'app_act_tri', methods: ['GET'])]
public function tri(ActivitesRepository $activitesRepository, string $criteria): Response
{
    $validCriteria = ['nomAct','prixAct'];
    
    if (!in_array($criteria, $validCriteria)) {
        throw $this->createNotFoundException('Invalid sorting criteria.');
    }

    $acti = $activitesRepository->findAllSortedBy($criteria);

    return $this->render('activites/index.html.twig', [
        'activites' => $acti,
        'currentCriteria' => $criteria,
    ]);
}

    #[Route('/name', name: 'app_activites_name', methods: ['GET'])]
    public function namefind(Request $request,ActivitesRepository $activitesRepository): Response
    {
        /*$name = "padle";*/
        $name = $request->query->get('activityName');
        $act = $activitesRepository->findBy(['nomAct' => $name]);

    return $this->render('activites/index.html.twig', ['activites' => $act]);
    }

    #[Route('/location', name: 'app_activites_location', methods: ['GET'])]
    public function locationfind(Request $request,ActivitesRepository $activitesRepository): Response
    {
        $location = $request->query->get('location');
        $act = $activitesRepository->findBy(['lieuAct' => $location]);

    return $this->render('activites/index.html.twig', ['activites' => $act]);
    }
/*
    #[Route('/back', name: 'activitesback_index', methods: ['GET'])]
    public function backofficeact(ActivitesRepository $activitesRepository, InscriptionRepository $inscriptionRepository): Response
    {
        return $this->render('activites/backoffice.html.twig', [
            'activites' => $activitesRepository->findAll(),
            'inscriptions' => $inscriptionRepository->findAll(),
            
        ]);
    }


    #[Route('/statact', name: 'stat_activite', methods: ['GET'])]
    public function statact(ActivitesRepository $activitesRepository, InscriptionRepository $inscriptionRepository): Response
    {
        return $this->render('activites/statactivite.html.twig');
    }
    #[Route('/get-activity-count-by-place', name: 'app_get_activity_count_by_place', methods: ['GET'])]
    public function getActivityCountByPlace(ActivitesRepository $activitesRepository): JsonResponse
    {
        $activityCountByPlace = $activitesRepository->getActivityCountByPlace();

        return new JsonResponse($activityCountByPlace);
    }
    #[Route('/get-activity-count-by-org', name: 'app_get_activity_count_by_org', methods: ['GET'])]
    public function getActivityCountByorg(ActivitesRepository $activitesRepository): JsonResponse
    {
        $activityCountByorg = $activitesRepository->getActivityCountByorg();

        return new JsonResponse($activityCountByorg);
    }
*/

    #[Route('/abonner/{id}', name: 'abonner_activite', methods: ['GET'])]
    public function Abonner_Activite($id): Response
    {
        return $this->redirectToRoute('app_inscription_new', ['id' => $id]);
    }

   /* #[Route('/new', name: 'app_activites_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $activite = new Activites();
        $form = $this->createForm(ActivitesType::class, $activite);
        $form->handleRequest($request);
        $imagedirectory = $this->getParameter('kernel.project_dir').'/public/uploads/images';
        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('images')->getData();
            if($imageFile){
                $newFilename = uniqid().'.'.$imageFile->guessExtension();
                $imageFile->move($imagedirectory,$newFilename);
                $activite->setImages($newFilename);


            }
            $entityManager->persist($activite);
            $entityManager->flush();

            return $this->redirectToRoute('app_activites_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('activites/new.html.twig', [
            'activite' => $activite,
            'form' => $form,
        ]);
    }*/



    #[Route('/{idAct}', name: 'app_activites_show', methods: ['GET', 'POST'])]
    public function show(ActivitesRepository $activitesRepository , Request $request, EntityManagerInterface $entityManager,$idAct, UserInterface $user): Response
    {
       

        $activite = $activitesRepository->find($idAct);
        $isFormSubmitted = false;
        $inscription = new Inscription();
        $inscription->setNbrTickes(1);
        $prix = $activite->getPrixAct();
        $form = $this->createForm(InscriptionType::class, $inscription);
        $form->handleRequest($request);
        $userId = $user->getIdUser();

        if ($form->isSubmitted() && $form->isValid()) {
            $inscription->setActivite($activite);
           
            $inscription->setUserins($user);
            $nbrTicketValue = $form->get('nbrTickes')->getData();
            $prix =  $prix *$nbrTicketValue;
            $inscription -> setFraitAbonnement($prix);
            dump($inscription);
            $entityManager->persist($inscription);
            $entityManager->flush();
            
            

            return $this->redirectToRoute('ticket', ['userId' => $userId], Response::HTTP_SEE_OTHER);

        }
        return $this->renderForm('activites/show.html.twig', [
            'activite' => $activite,
            'inscription' => $inscription,
            'form' => $form,
            'id'=>$idAct,
            'prixactivite'=>$prix,
            'userid'=> $userId,
            
        ]);
    }

        
    #[Route('/ticket/{userId}', name: 'ticket', methods: ['GET', 'POST'])]
    public function showTicket(InscriptionRepository $inscriptionRepository,ActivitesRepository $activitesRepository , $userId): Response
    {$rdvs[]= [];
        $this->addFlash('success', 'Reservation made successfully');$rdvs[]= [];
        $this->addFlash('success', 'Reservation made successfully');
        $inscriptions = $inscriptionRepository->findByUserId($userId);
        foreach ($inscriptions as $i) {
            $periode = $i->getActivite()->getPeriode();
            [$start, $end] = explode(' - ', $periode);
            $startDate = \DateTime::createFromFormat('d/m/Y', $start);
            $endDate = \DateTime::createFromFormat('d/m/Y', $end);
            $formattedStart = $startDate->format('Y-m-d');
            $formattedEnd = $endDate->format('Y-m-d');
            $rdvs[]= [
                'title' => $i->getActivite()->getNomAct(),
                'start'=> $formattedStart,
                'end' => $formattedEnd,
                'color' => '#009879',
                


            ] ; 
        }
        $data = json_encode($rdvs);
        return $this->render('inscription/ticket.html.twig',[
            'inscription' => $inscriptions,
            'data' =>$data,
            'start'=> $formattedStart,
            'end' => $formattedEnd,
            'data' =>$data,
            'start'=> $formattedStart,
            'end' => $formattedEnd,
            
        ]);
    }
    


    


    #[Route('/load-ticket-content/{inscriptionId}', name: 'load-ticket-content', methods: ['GET'])]
public function loadTicketContent(InscriptionRepository $inscriptionRepository, $inscriptionId): Response
{   $writer = new PngWriter();

    $inscription = $inscriptionRepository->find($inscriptionId);
    $ticketData = $inscription->getTicketData();
    $qrCode = new QrCode($ticketData);
   //$qrCode = new QrCode("heelo world");

  
    $pngResult = $writer->write($qrCode);

    $qrCodeImage = base64_encode($pngResult->getString());

    
    return $this->render('inscription/ticket_content.html.twig', [
        'inscription' => $inscription,
        'qrCodeImage' => $qrCodeImage,
    ]);
}



    #[Route('/{idAct}/edit', name: 'app_activites_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Activites $activite, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ActivitesType::class, $activite);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $entityManager->flush();

            return $this->redirectToRoute('activitesback_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('activites/edit.html.twig', [
            'activite' => $activite,
            'form' => $form,
        ]);
    }

    #[Route('/{idAct}/delete/delete', name: 'app_activites_delete', methods: ['POST'])]
    public function delete(Request $request, Activites $activite, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$activite->getIdAct(), $request->request->get('_token'))) {
            $entityManager->remove($activite);
            $entityManager->flush();
        }

        return $this->redirectToRoute('activitesback_index', [], Response::HTTP_SEE_OTHER);
    }



    



}
