<?php
/*
namespace App\Security;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Http\Authorization\AccessDeniedHandlerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class AccessDeniedHandler implements AccessDeniedHandlerInterface
{
    private $urlGenerator;

    public function __construct(UrlGeneratorInterface $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
    }

    public function handle(Request $request, AccessDeniedException $accessDeniedException): Response
    {
        // Customize the access denied behavior, e.g., redirect with a flash message
        $url = $this->urlGenerator->generate('app_home');
        $response = new RedirectResponse($url);
        $response->headers->set('Refresh', '5;url=' . $url); // Optional: Refresh header

        // Flash message to inform the user
        $request->getSession()->getFlashBag()->add('warning', 'You do not have access to this page.');

        return $response;
    }
}*/