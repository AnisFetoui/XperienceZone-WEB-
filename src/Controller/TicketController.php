<?php

namespace App\Controller;

use App\Entity\Ticket;
use App\Entity\Evennement;
use App\Form\TicketType;
use App\Repository\TicketRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\EvenementRepository;
use Dompdf\Dompdf;
use Dompdf\Options;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use App\Service\TwilioService;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Security\Core\User\UserInterface;




#[Route('/ticket')]
class TicketController extends AbstractController
{
    #[Route('/', name: 'app_ticket_index', methods: ['GET'])]
    public function index(TicketRepository $ticketRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $allTickets = $ticketRepository->findAll();

        // Paginate the results
        $tickets = $paginator->paginate(
            $allTickets, // Query results (e.g., all tickets)
            $request->query->getInt('page', 1), // Current page number, defaults to 1
            5// Number of items per page
        );
        return $this->render('ticket/index.html.twig', [
            'tickets' => $tickets,
        ]);
    }



    #[Route('/{idEvent}/new', name: 'app_ticket_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, EvenementRepository $evenementRepository,TwilioService $twilioService,UserInterface $user,$idEvent): Response
    {
        $ticket = new Ticket();

 // Récupérer l'événement en fonction de $idEvent
/* $evenement = $evenementRepository->findOneBy(['idEvent' => $idEvent]);

 if ($evenement) {
     $ticket->setEvenement($evenement);
 }*/
     
        $form = $this->createForm(TicketType::class, $ticket);
        
        $form->handleRequest($request);
        $imagedirectory = $this->getParameter('kernel.project_dir').'/public/uploads/images'; // Remplacez par le chemin réel de votre répertoire
        if ($form->isSubmitted() && $form->isValid()) {
            
         
            $ticket->setUserticket($user);
        //$idEvent = $form->get('evenement')->getData();
        $evenement = $evenementRepository->find($idEvent);
        if (!$evenement) {
            throw $this->createNotFoundException('Event not found');
        }
        $ticket->setImage($evenement->getImage());

        // Associer l'événement au ticket
    
        $ticket->setEvenement($evenement);
       // $session->set('key', 'value');
            $entityManager->persist($ticket);
            $entityManager->flush();
            $to = '+21692103963'; // Static phone number
            $message = 'Hello We are excited to inform you that your ticket for the event has been successfully added.Thank you for choosing our platform! If you have any questions or concerns, feel free to reach out see you there'; // Modify the message as needed
            $twilioService->sendSMS($to, $message);
            $idTicket = $ticket->getIdTicket();
            // Récupérer des données depuis la session
       // $valueFromSession = $session->get('key');
            return $this->redirectToRoute('app_ticket_show',  ['idTicket' => $idTicket], Response::HTTP_SEE_OTHER);
        }
        return $this->renderForm('ticket/new.html.twig', [
            'ticket' => $ticket,
            'form' => $form,
        ]);
    }



    #[Route('/{idTicket}', name: 'app_ticket_show', methods: ['GET'])]
    public function show(Ticket $ticket): Response
    {
        return $this->render('ticket/show.html.twig', [
            'ticket' => $ticket,
        ]);
    }


    #[Route('/{idTicket}/show', name: 'app_ticket_showback', methods: ['GET'])]
    public function showw(Ticket $ticket): Response
    {
        return $this->render('ticket/showback.html.twig', [
            'ticket' => $ticket,
        ]);
    }


    
    #[Route('/{idTicket}/edit', name: 'app_ticket_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Ticket $ticket, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TicketType::class, $ticket);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_evenement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('ticket/edit.html.twig', [
            'ticket' => $ticket,
            'form' => $form,
        ]);
    }

    #[Route('/{idTicket}/delete', name: 'app_ticket_deleteback', methods: ['POST'])]
    public function deletee(Request $request, Ticket $ticket, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$ticket->getIdTicket(), $request->request->get('_token'))) {
            $entityManager->remove($ticket);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_ticket_index', [], Response::HTTP_SEE_OTHER);
    }









    #[Route('/{idTicket}/editt', name: 'app_ticket_editback', methods: ['GET', 'POST'])]
    public function editt(Request $request, Ticket $ticket, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TicketType::class, $ticket);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_ticket_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('ticket/editback.html.twig', [
            'ticket' => $ticket,
            'form' => $form,
        ]);
    }

   





    #[Route('/{idTicket}', name: 'app_ticket_delete', methods: ['POST'])]
    public function delete(Request $request, Ticket $ticket, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$ticket->getIdTicket(), $request->request->get('_token'))) {
            $entityManager->remove($ticket);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_evenement_index', [], Response::HTTP_SEE_OTHER);
    }


    /*#[Route('/pdf', name: 'ticket_pdf',methods: ['GET'])]
    public function ticket_pdf(TicketRepository $Repository ): Response
    {
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');
        $pdfOptions->setIsRemoteEnabled(true);
        // Instantiate Dompdf with our options
        $dompdf =new Dompdf($pdfOptions);

        $ticket = $repository->findAll();

        // Retrieve the HTML generated in our twig file
        $html = $this->renderView('ticket/pdf.html.twig', [
            'ticket' => $ticket,
        ]);
        $dompdf->loadHtml($html);

        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser (force download)
        $dompdf->stream("ticket.pdf", ["Attachment" => true]);
           // Return a Response object instead
    return new Response();
    }*/

  /*  #[Route('/pdf', name: 'ticket_pdf', methods: ['GET'])]
    public function ticket_pdf(Ticket $ticket): Response
    {
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');
        $pdfOptions->setIsRemoteEnabled(true);
        
        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);
        
        // Retrieve the HTML generated in our twig file
        $html = $this->renderView('ticket/pdf.html.twig', [
            'ticket' => $ticket,
        ]);
        $dompdf->loadHtml($html);
        
        $dompdf->setPaper('A4', 'portrait');
        
        // Render the HTML as PDF
        $dompdf->render();
        
        // Output the generated PDF to Browser (force download)
        $dompdf->stream("ticket.pdf", ["Attachment" => true]);
        
        // Return a Response object instead
        return new Response();
    }*/

    /*#[Route('/{idTicket}/pdf', name: 'app_ticket_pdf', methods: ['GET'])]
    public function generatePdf(Ticket $ticket): Response
    {
        if (!$ticket) {
            throw $this->createNotFoundException('Ticket not found');
        }
        // Créer une instance de Dompdf
        $dompdf = new Dompdf();
    
        // Options de configuration (optionnelles)
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isPhpEnabled', true);
    
        $dompdf->setOptions($options);
    
        // Générer le contenu HTML du PDF
        $html = $this->renderView('ticket/pdf.html.twig', ['ticket' => $ticket]);
    
        // Charger le HTML dans Dompdf
        $dompdf->loadHtml($html);
    
        // Rendre le document PDF
        $dompdf->render();
    
        // Générer une réponse avec le contenu PDF
        $response = new Response($dompdf->output());
        $response->headers->set('Content-Type', 'application/pdf');
    
        // Télécharger le fichier PDF au lieu de l'afficher dans le navigateur (facultatif)
        //$response->headers->set('Content-Disposition', 'inline; filename="ticket.pdf"');
    
        return $response;
    }

*/



}
