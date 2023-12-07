<?php

namespace App\Controller;

use App\Repository\UserrRepository;
use App\Entity\Userr;
use App\Form\ForgotPasswordType;
use Swift_Mailer;
use Swift_Message;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;


class SecurityController extends AbstractController
{
    #[Route('/security', name: 'app_security')]
    public function index(): Response
    {
        return $this->render('security/login.html.twig', [
            'controller_name' => 'SecurityController',
        ]);
    }

    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils, Request $request,TokenGeneratorInterface  $tokenGenerator): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();
    
        $token = $request->query->get('token');
    
        if ($token) {
            $user = $this->getDoctrine()->getRepository(Userr::class)->findOneBy(['reset_token' => $token]);
    
            if ($user && !$user->isTokenExpired()) {
                
                $user->setResetToken(null);
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($user);
                $entityManager->flush();
    
                $this->addFlash('success', 'Password reset successful. You can now log in with your new password.');
            } else {
                $this->addFlash('danger', 'Invalid or expired reset token.');
            }
        }else {
            
            $user = $this->getUser();
    
            if ($user instanceof Userr) {
                $token = $tokenGenerator->generateToken();
                if (in_array('ROLE_CLIENT', $user->getRoles())) {
                    return $this->redirectToRoute('app_home');
                }
                if (in_array('ROLE_ADMIN', $user->getRoles())) {
                    return $this->redirectToRoute('app_utilisateur_index');
                }
    
                $user->setResetToken($token);
                
    
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($user);
                $entityManager->flush();
            }
            
        }
        
        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }
    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): \Symfony\Component\HttpFoundation\RedirectResponse
    {
        return $this->redirectToRoute("app_login");
    }

     
    #[Route('/forgot', name: 'forgot')]
    
    public function forgotPassword(Request $request, UserrRepository $userRepository,Swift_Mailer $mailer, TokenGeneratorInterface  $tokenGenerator)
    {


        $form = $this->createForm(ForgotPasswordType::class);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $donnees = $form->getData();


            $user = $userRepository->findOneBy(['mail'=>$donnees]);
            if(!$user) {
                $this->addFlash('Warning','This email address does not exist.');
                return $this->redirectToRoute("forgot");

            }
            $token = $tokenGenerator->generateToken();

            try{
                $user->setResetToken($token);
                $entityManger = $this->getDoctrine()->getManager();
                $entityManger->persist($user);
                $entityManger->flush();




            }catch(\Exception $exception) {
                $this->addFlash('warning','An error occurred :'.$exception->getMessage());
                return $this->redirectToRoute("app_login");


            }

            $url = $this->generateUrl('app_reset_password',array('token'=>$token),UrlGeneratorInterface::ABSOLUTE_URL);

            //BUNDLE MAILER
            $message = (new Swift_Message('Forgotten Password'))
                ->setFrom('naceur.akacha@esprit.tn')
                ->setTo($user->getMail())
                ->setBody("<p> Hello,</p> a password reset request has been made. Please click on the following link:".$url,
                "text/html");

            //send mail
            $mailer->send($message);
            $this->addFlash('message','Password reset email sent:');
        //    return $this->redirectToRoute("app_login");

        }

        return $this->render("security/forgotPassword.html.twig",['form'=>$form->createView()]);
    }


    #[Route('/resetpassword/{token}', name: 'app_reset_password')]
    public function resetpassword(Request $request,string $token, UserPasswordEncoderInterface  $passwordEncoder)
    {   

        $user = $this->getDoctrine()->getRepository(Userr::class)->findOneBy(['reset_token' => $token]);
        if($user == null ) {
            $this->addFlash('danger','TOKEN INCONNU');
            return $this->redirectToRoute("app_login");

        }

        if($request->isMethod('POST')) {
            $user->setResetToken(null);

            $user->setPassword($passwordEncoder->encodePassword($user,$request->request->get('password')));
            $entityManger = $this->getDoctrine()->getManager();
            $entityManger->persist($user);
            $entityManger->flush();

            $this->addFlash('message','Password Updated :');
            return $this->redirectToRoute("app_login");

        }
        else {
            return $this->render("security/resetPassword.html.twig",['token'=>$token]);

        }
    }

    #[Route('/resetpasswordC/{token}', name: 'app_reset_password_Client')]
    public function resetpasswordClient(Request $request,string $token, UserPasswordEncoderInterface  $passwordEncoder)
    {

        $user = $this->getDoctrine()->getRepository(Userr::class)->findOneBy(['reset_token'=>$token]);

        if($user == null ) {
            $this->addFlash('danger','TOKEN INCONNU');
            return $this->redirectToRoute("app_login");

        }

        if($request->isMethod('POST')) {
            $user->setResetToken(null);

            $user->setPassword($passwordEncoder->encodePassword($user,$request->request->get('password')));
            $entityManger = $this->getDoctrine()->getManager();
            $entityManger->persist($user);
            $entityManger->flush();

            $this->addFlash('message','Password Updated :');
            return $this->redirectToRoute("app_home");

        }
        else {
            return $this->render("security/resetPasswordClient.html.twig",['token'=>$token]);

        }
    }

    #[Route('/resetpasswordA/{token}', name: 'app_reset_password_Admin')]
    public function resetpasswordAdmin(Request $request,string $token, UserPasswordEncoderInterface  $passwordEncoder)
    {
 
        $user = $this->getDoctrine()->getRepository(Userr::class)->findOneBy(['reset_token'=>$token]);

        if($user == null ) {
            $this->addFlash('danger','TOKEN INCONNU');
            return $this->redirectToRoute("app_login");

        }

        if($request->isMethod('POST')) {
            $user->setResetToken(null);

            $user->setPassword($passwordEncoder->encodePassword($user,$request->request->get('password')));
            $entityManger = $this->getDoctrine()->getManager();
            $entityManger->persist($user);
            $entityManger->flush();

            $this->addFlash('message','Password Updated :');
            return $this->redirectToRoute("app_utilisateur_index");

        }
        else {
            return $this->render("security/resetPassword.html.twig",['token'=>$token]);

        }
    }

    #[Route(path: '/generate_and_redirect_Admin', name: 'generate_and_redirectAdmin')]
    public function generateAndRedirectActionAdmin(TokenGeneratorInterface $tokenGenerator): Response
    {
        $user = $this->getUser();
    
        if ($user instanceof Userr) {
            // Generate a new token
            $token = $tokenGenerator->generateToken();
    
            // Set the generated token in the user entity
            $user->setResetToken($token);
    
            // Persist the changes to the database
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
    
            // Redirect to the reset password page with the generated token
            return $this->redirectToRoute('app_reset_password_Admin', ['token' => $user->getResetToken()]);
        }
    
        // Handle the case where the user is not authenticated
        return $this->redirectToRoute('app_utilisateur_index');
    }

    #[Route(path: '/generate_and_redirect_Client', name: 'generate_and_redirectClient')]
    public function generateAndRedirectActionClient(TokenGeneratorInterface $tokenGenerator): Response
    {
        $user = $this->getUser();
    
        if ($user instanceof Userr) {
            // Generate a new token
            $token = $tokenGenerator->generateToken();
    
            // Set the generated token in the user entity
            $user->setResetToken($token);
    
            // Persist the changes to the database
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
    
            // Redirect to the reset password page with the generated token
            return $this->redirectToRoute('app_reset_password_Client', ['token' => $user->getResetToken()]);
        }
    
        // Handle the case where the user is not authenticated
        return $this->redirectToRoute('app_home');
    }

}
