<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Routing\RouterInterface;

class LoginSuccessHandler implements AuthenticationSuccessHandlerInterface
{
    private RouterInterface $router;

    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token): Response
    {
        $user = $token->getUser();

        // Rediriger les administrateurs vers le dashboard admin
        if (in_array('ROLE_ADMIN', $user->getRoles())) {
            return new \Symfony\Component\HttpFoundation\RedirectResponse(
                $this->router->generate('admin_dashboard')
            );
        }

        // Rediriger les utilisateurs normaux vers leur profil
        return new \Symfony\Component\HttpFoundation\RedirectResponse(
            $this->router->generate('app_profile')
        );
    }
}
