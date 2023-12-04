<?php
 
namespace App\Controller;
use App\Entity\Ticket;
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

 
class PdfGeneratorController extends AbstractController
{
    #[Route('/pdf/generator/{idTicket}', name: 'app_pdf_generator')]
    public function generatePdf(Ticket $ticket): Response
    {
        if (!$ticket) {
            throw $this->createNotFoundException('Ticket not found');
        }
    
        $data = [
                'ticket' => $ticket,
            'numTicket'      => $ticket->getNumTicket(),
            'prix'           => $ticket->getPrix(),
            'evenement'      => [
                'nomEvent'   => $ticket->getEvenement()->getNomEvent(),
                'lieuEvent'  => $ticket->getEvenement()->getLieuEvent(),
                'dateEvent'  => $ticket->getEvenement()->getDateEvent()->format('d/m/Y'),
                'heureEvent' => $ticket->getEvenement()->getHeureEvent(),
            ],
        ];
    
        $html = $this->renderView('pdf_generator/index.html.twig', $data);
    
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isPhpEnabled', true);

         // Générer le PDF
        $dompdf = new Dompdf();
        $dompdf->setOptions($options);
        $dompdf->loadHtml($html);
        $dompdf->render();
    
        return new Response(
            $dompdf->stream('ticket', ["Attachment" => false]),
            Response::HTTP_OK,
            ['Content-Type' => 'application/pdf']
        );
    }
 
   
}

