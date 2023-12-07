<?php

namespace App\Controller;

use App\Entity\Evenement;
use App\Form\EvenementType;
use App\Repository\EvenementRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\FileType;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use VictorPrad\RecaptchaBundle\Validator\Constraints as Recaptcha;


#[Route('/evenement')]
class EvenementController extends AbstractController
{
   
    private $session;

    //  service SessionInterface dans le constructeur
    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }



    #[Route('/', name: 'app_evenement_index', methods: ['GET'])]
public function index(EvenementRepository $evenementRepository, Request $request, PaginatorInterface $paginator): Response
{
    $searchTerm = $request->query->get('searchTerm');

    // Utilisez ce paramètre pour rechercher dans la base de données
    $evenementsQuery = $evenementRepository->searchByTerm($searchTerm);

    // Paginer les résultats
    $evenements = $paginator->paginate(
        $evenementsQuery,
        $request->query->getInt('page', 1),
        5 // Nombre d'éléments par page
    );

    return $this->render('evenement/index.html.twig', [
        'evenements' => $evenements,
        'searchTerm' => $searchTerm,
    ]);
}


        
    
   /*
     #[Route('/', name: 'app_evenement_index', methods: ['GET'])]
    public function index(EvenementRepository $evenementRepository, Request $request, PaginatorInterface $paginator): Response
    {   
        
        $allEvenements = $evenementRepository->findAll();

        // Paginate the results
        $evenements = $paginator->paginate(
            $allEvenements, // Query results (e.g., all events)
            $request->query->getInt('page', 1), // Current page number, defaults to 1
            5 // Number of items per page
        );
    
        return $this->render('evenement/index.html.twig', [
            'evenements' => $evenements,
        ]);
     
    }
  #[Route('/miniar', name: 'app_evenement_indexback', methods: ['GET'])]
  public function indexback(EvenementRepository $evenementRepository, Request $request, PaginatorInterface $paginator): Response
  {   
      $allEvenements = $evenementRepository->findAll();

      // Paginate the results
      $evenements = $paginator->paginate(
          $allEvenements, // Query results (e.g., all events)
          $request->query->getInt('page', 1), // Current page number, defaults to 1
          4 // Number of items per page
      );
      $criteria = $request->query->get('criteria', 'idEvent'); 
        
        
        $validCriteria = ['idEvent', 'lieuEvent', 'dateEvent', 'heureEvent', 'organisateur']; 
        
        if (!in_array($criteria, $validCriteria)) {
            $criteria = 'idEvent';
        }

        $evenement = $evenementRepository->findBy([], [$criteria => 'ASC']);
  
      return $this->render('evenement/indexback.html.twig', [
          'evenements' => $evenements,
      ]);
    
      
  }   */

  #[Route('/{idEvent}/editback', name: 'app_evenement_editback', methods: ['GET', 'POST'])]
  public function editback(Request $request, Evenement $evenement, EntityManagerInterface $entityManager): Response
  {
      $form = $this->createForm(EvenementType::class, $evenement);
      $form->handleRequest($request);
      $imagedirectory = $this->getParameter('kernel.project_dir').'/public/uploads/images'; 

      if ($form->isSubmitted() && $form->isValid()) {
            //handle image apload 
            $imageFile= $form->get('image')->getData();
            if ($imageFile){
                //generate a unique name for the image 
                $newFilename = uniqid().'.'.$imageFile->guessExtension();
                $imageFile->move( $imagedirectory,$newFilename);
                $evenement->setImage($newFilename);
            }
        
          $entityManager->flush();

          return $this->redirectToRoute('app_evenement_indexback', [], Response::HTTP_SEE_OTHER);
      }

      return $this->renderForm('evenement/editback.html.twig', [
          'evenement' => $evenement,
          'form' => $form,
      ]);
  }





  
  #[Route('/newback', name: 'app_evenement_newback', methods: ['GET', 'POST'])]
  public function newback(Request $request, EntityManagerInterface $entityManager): Response
  {
      $evenement = new Evenement();
      $form = $this->createForm(EvenementType::class, $evenement);
      $form->handleRequest($request);
      $imagedirectory = $this->getParameter('kernel.project_dir').'/public/uploads/images'; 
      if ($form->isSubmitted() && $form->isValid()) {
          //handle image apload 
          $imageFile= $form->get('image')->getData();
          if ($imageFile){
              //generate a unique name for the image 
              $newFilename = uniqid().'.'.$imageFile->guessExtension();
              $imageFile->move( $imagedirectory,$newFilename);
              $evenement->setImage($newFilename);
          }
          $entityManager->persist($evenement);
          $entityManager->flush();

          return $this->redirectToRoute('app_evenement_indexback', [], Response::HTTP_SEE_OTHER);
      }
      return $this->renderForm('evenement/newback.html.twig', [
          'evenement' => $evenement,
          'form' => $form,
      ]);
  }


  #[Route('/show/{idEvent}', name: 'app_evenement_showback', methods: ['GET'])]
  public function showback(Evenement $evenement): Response
  {
      return $this->render('evenement/showback.html.twig', [
          'evenement' => $evenement,
      ]);
  }

 

  #[Route('/delete/{idEvent}', name: 'app_evenement_deleteback', methods: ['POST'])]
  public function deleteback(Request $request, Evenement $evenement, EntityManagerInterface $entityManager): Response
  {
      if ($this->isCsrfTokenValid('delete'.$evenement->getIdEvent(), $request->request->get('_token'))) {
          $entityManager->remove($evenement);
          $entityManager->flush();
      }
      return $this->redirectToRoute('app_evenement_indexback', [], Response::HTTP_SEE_OTHER);
  }

    

    #[Route('/new', name: 'app_evenement_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $evenement = new Evenement();
        $form = $this->createForm(EvenementType::class, $evenement);
        $form->handleRequest($request);
        $imagedirectory = $this->getParameter('kernel.project_dir').'/public/uploads/images'; // Remplacez par le chemin réel de votre répertoire
        if ($form->isSubmitted() && $form->isValid()) {
            //handle image apload 
            $imageFile= $form->get('image')->getData();
            if ($imageFile){
                //generate a unique name for the image 
                $newFilename = uniqid().'.'.$imageFile->guessExtension();
                $imageFile->move( $imagedirectory,$newFilename);
                $evenement->setImage($newFilename);
            }
            $entityManager->persist($evenement);
            $entityManager->flush();
            $this->session->getFlashBag()->add('success', 'The event is successfully added!');
            return $this->redirectToRoute('app_evenement_index', [], Response::HTTP_SEE_OTHER);
        }
        return $this->renderForm('evenement/new.html.twig', [
            'evenement' => $evenement,
            'form' => $form,
        ]);
    }


    #[Route('/{idEvent}', name: 'app_evenement_show', methods: ['GET'])]
    public function show(Evenement $evenement): Response
    {
       return $this->render('evenement/show.html.twig', [
            'evenement' => $evenement,
        ]);
    }

    #[Route('/{idEvent}/edit', name: 'app_evenement_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Evenement $evenement, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(EvenementType::class, $evenement);
        $form->handleRequest($request);
        $imagedirectory = $this->getParameter('kernel.project_dir').'/public/uploads/images'; // Remplacez par le chemin réel de votre répertoire

        if ($form->isSubmitted() && $form->isValid()) {
             //handle image apload 
             $imageFile= $form->get('image')->getData();
             if ($imageFile){
                 //generate a unique name for the image 
                 $newFilename = uniqid().'.'.$imageFile->guessExtension();
                 $imageFile->move( $imagedirectory,$newFilename);
                 $evenement->setImage($newFilename);
             }
            $entityManager->flush();

            return $this->redirectToRoute('app_evenement_index', [], Response::HTTP_SEE_OTHER);
        }
        return $this->renderForm('evenement/edit.html.twig', [
            'evenement' => $evenement,
            'form' => $form,
        ]);
    }
    



    #[Route('/{idEvent}', name: 'app_evenement_delete', methods: ['POST'])]
    public function delete(Request $request, Evenement $evenement, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$evenement->getIdEvent(), $request->request->get('_token'))) {
            $entityManager->remove($evenement);
            $entityManager->flush();
        }
        return $this->redirectToRoute('app_evenement_index', [], Response::HTTP_SEE_OTHER);
    }
    

    /*#[Route('/details/{idEvent}', name: 'app_evenement_details', methods: ['GET'])]
    public function details(string $idEvent,EvenementRepository $evenementRepository): Response
    {
         $evenement = $evenementRepository->find($idEvent);
    
        return $this->render('evenement/details.html.twig', [
            'evenement' => $evenement,
        ]);
    }

    #[Route('/evenement/search', name: 'app_evenement_search')]
    public function searchPage(): Response
    {
        return $this->render('evenement/search.html.twig');
    }

    #[Route('/search', name: 'ajax_search', methods: ['GET'])]
public function search(Request $request, EvenementRepository $evenementRepository): JsonResponse
{
    $searchString = $request->query->get('q');
    $evenements = $evenementRepository->findevenementByString($searchString);

    $evenementDetails = [];
    foreach ($evenements as $evenement) {
        $evenementDetails[] = [
            'idEvent' => $evenement->getIdEvent(),
            'details' => $evenement->getDetails(),
            
        ];
    }
    return new JsonResponse(['evenements' => $evenementDetails]);
}*/
   

  /*  #[Route('/search', name:'app_search')]

  public function search(Request $request): Response
  {
    $nomEvent = $request->query->get('nom_event');
    $lieuEvent = $request->query->get('lieu_event');
    $em = $this->getDoctrine()->getManager();
    
    $evenements = $em->getRepository(Evenement::class)->findByNomAndLieu($nomEvent, $lieuEvent);

    return $this->render('evenement/index.html.twig', [
        'evenements' => $evenements,
        'nomEvent' => $nomEvent,
        'lieuEvent' => $lieuEvent,
    ]);
}*/
  

#[Route('/evenement/search', name: 'app_search', methods: ['GET'])]
public function search(Request $request, EvenementRepository $evenementRepository): JsonResponse
{
    $searchTerm = $request->query->get('searchTerm'); // Utilisation de query au lieu de request
    $evenements = $evenementRepository->search($searchTerm);

    $data = [
        'evenements' => $evenements,
    ];

    return $this->json($data);
}






}