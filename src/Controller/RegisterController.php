<?php

namespace App\Controller;

    use App\Entity\Utilisateur;
    use App\Form\RegisterType;
    use App\Repository\UserRepository;
    use Doctrine\ORM\EntityManagerInterface;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Annotation\Route;
    
    
    class RegisterController extends AbstractController
    {
        #[Route('/registerr', name: 'app_register')]
        public function index(Request $request, EntityManagerInterface $entityManager): Response
        {
            $utilisateur = new Utilisateur();
            $form = $this->createForm(RegisterType::class, $utilisateur);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager->persist($utilisateur);
                $entityManager->flush();

                return $this->redirectToRoute('app_login', [], Response::HTTP_SEE_OTHER);
            }

            return $this->render('register/index.html.twig', [
                'controller_name' => 'RegisterController',
                'form' => $form->createView(),
            ]);
            
            
        }

        public function new(Request $request, EntityManagerInterface $entityManager): Response
        {
            
        }
    }
