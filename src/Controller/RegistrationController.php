<?php
namespace App\Controller;

use App\Entity\Userr;
use App\Repository\UserrRepository;
use App\Form\RegisterType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Form\FormError;

class RegistrationController extends AbstractController
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    #[Route("/registration", name: "registration")]
    public function index(Request $request, UserrRepository $userRepository)
    {
        $user = new Userr();

        $form = $this->createForm(RegisterType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $email = $form->get('mail')->getData();

            $userA = $userRepository->findOneBy(['mail' => $email]);
    
            if ($userA) {
                $form->get('mail')->addError(new FormError('Cette adresse existe déjà.'));

            }else{
                
            $user->setPassword($this->passwordEncoder->encodePassword($user, $user->getPassword()));

            $user->setRoles(['ROLE_CLIENT']);

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('app_login');
        }
    }
        return $this->render('registration/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}