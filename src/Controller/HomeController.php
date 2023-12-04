<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use App\Form\UserProfileType;
use App\Entity\Userr;
use App\Repository\UserrRepository;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
    
#[Route('/home')]
class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        return $this->render('base.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    #[Route('/ProfileC/{idUser}', name: 'app_ProfileClient', methods: ['GET', 'POST'])]
    public function ProfileClient(Request $request,UserrRepository $UR, EntityManagerInterface $entityManager, $idUser ): Response
    {
        $utilisateur = $UR->find($idUser);
        $form = $this->createForm(UserProfileType::class, $utilisateur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
        }

        

        return $this->renderForm('utilisateur/ProfileClient.html.twig', [
            'utilisateur' => $utilisateur,
            'form' => $form,
        ]);
    }

}
