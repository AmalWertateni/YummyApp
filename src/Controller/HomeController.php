<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        // Si l'utilisateur est déjà connecté
        if ($this->getUser()) {
            // Vérifier si c'est un admin
            if ($this->isGranted('ROLE_ADMIN')) {
                return $this->redirectToRoute('admin_dashboard');
            }
            // Pour les utilisateurs normaux, afficher la page d'accueil normale
            return $this->render('home/index.html.twig');
        }

        // Pour les utilisateurs non connectés, afficher la page d'accueil publique
        return $this->render('home/public.html.twig');
    }
}
