<?php

namespace App\Controller;
use App\Entity\Userr;
use App\Entity\Message;
use App\Form\MessageType;
use App\Repository\ChannelRepository;
use App\Repository\MessageRepository;
use App\Repository\UserrRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Translation\TranslatableMessage;
use Eckinox\TinymceBundle\TinymceBundle;
use Twig\TwigFilter;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[Route('/message')]
class MessageController extends AbstractController
{
   #[Route('/', name: 'app_message_index', methods: ['GET'])]
   public function index(MessageRepository $messageRepository, PaginatorInterface $paginator, Request $request): Response
   {
       
        $allMessage = $messageRepository->findAll();

      
        $messages = $paginator->paginate(
            $allMessage, 
            $request->query->getInt('page', 1), 
          
        );
        return $this->render('message/index.html.twig', [
            'messages' => $messages,
    
       ]);
   }

    
    #[Route('/new', name: 'app_message_new', methods: ['GET', 'POST'])]
   public function new(Request $request, EntityManagerInterface $entityManager,UserrRepository $userRepository ,UserInterface $user,ChannelRepository $channelRepository) : Response
    {   $currentUser = new Userr();
        $message = new Message();
        $message->setUtilisateur($user);
        $message->setHeurEnvoiMsg(new \DateTime()) ;
        $currentUser = $message->getUtilisateur();
 
        $channel=$channelRepository->findOneBy(['nomCh'=> "ariana"]);  
        $message->setChannel($channel) ;
        $form = $this->createForm(MessageType::class, $message);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
          
        $content = $message->getContenuMsg();
        // Vérifier si le contenu du message contient des mots indésirables
        if ($this->containsForbiddenWords($content)) {
            // Bloquer l'envoi du message indésirable
            return new Response('"The message contains forbidden words 
            Next time, you will be blocked', Response::HTTP_BAD_REQUEST);
        }
            $entityManager->persist($message);
            $entityManager->flush();
         
            return $this->redirectToRoute('app_message_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('message/new.html.twig', [
            'message' => $message,
            'form' => $form,
        ]);
    }
   
    #[Route('/{idMsg}/edit', name: 'app_message_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Message $message, EntityManagerInterface $entityManager, UserrRepository $userRepository,UserInterface $user): Response
    {
        $currentUser = new Userr();
       // $message = new Message();
        $message->setUtilisateur($user);
        $message->setHeurEnvoiMsg(new \DateTime()) ;
        $currentUser = $message->getUtilisateur();;
       
        if ($currentUser !== null && $currentUser->getIdUser() === $message->getUtilisateur()->getIdUser()) {
        $form = $this->createForm(MessageType::class, $message);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $content = $message->getContenuMsg();
          
            if ($this->containsForbiddenWords($content)) {
               
                return new Response('"The message contains forbidden words 
                Next time, you will be blocked', Response::HTTP_BAD_REQUEST);
            }
             $entityManager->flush();
            return $this->redirectToRoute('app_message_index', [], Response::HTTP_SEE_OTHER);
        }
        return $this->renderForm('message/edit.html.twig', [
            'message' => $message,
            'form' => $form,
            
        ]);
    } else {
        return new JsonResponse(['Sorry! Thats not your message, so you cannot modify it']);
    }

    }


    #[Route('/{idMsg}', name: 'app_message_show', methods: ['GET'])]
    public function show(Message $message): Response
    {
        return $this->render('message/show.html.twig', [
            'message' => $message,
        ]);
    }
 
    #[Route('/{idMsg}', name: 'app_message_delete', methods: ['POST'])]
    public function delete(Request $request, Message $message, EntityManagerInterface $entityManager, UserrRepository $userRepository,UserInterface $user): Response
{
    $currentUser = new Userr();
     $message->setUtilisateur($user);
     $currentUser = $message->getUtilisateur();
    if ($currentUser !== null && $currentUser->getIdUser() === $message->getUtilisateur()->getIdUser()) {
        if ($this->isCsrfTokenValid('delete' . $message->getIdMsg(), $request->request->get('_token'))) {
            $entityManager->remove($message);
            $entityManager->flush();
    } else {
        
        return new JsonResponse(['Sorry ! thats not your comment so you cannot delete this message']);
    }

    return $this->redirectToRoute('app_message_index', [], Response::HTTP_SEE_OTHER);
}
}


private function containsForbiddenWords(string $content): bool
{
    $forbiddenWords = ['vulgaire', 'idiot', 'raciste', 'violance', 'ugly',  ];

    foreach ($forbiddenWords as $word) {
        if (stripos($content, $word) !== false) {
            return true;
        }
    }

    return false;
}
}
