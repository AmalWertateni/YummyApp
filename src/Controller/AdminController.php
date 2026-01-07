<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    #[Route('/admin', name: 'admin_dashboard')]
    public function index(): Response
    {
        // =========================
        // Statistiques
        // =========================
        $stats = [
            'users' => 150,
            'categories' => 5,
            'plats' => 30,
            'tables' => 15,
            'reservations' => 120,
            'commandes' => 300,
            'reservations_today' => 12,
            'orders_pending' => 8,
            'revenue_month' => 12500.50,
        ];

        // =========================
        // Données pour le graphique des réservations (7 derniers jours)
        // =========================
        $reservationsData = [
            'labels' => ['Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam', 'Dim'],
            'data' => [12, 19, 8, 15, 22, 18, 25],
            'colors' => ['#4e73df']
        ];

        // =========================
        // Données pour le graphique des statuts de commandes
        // =========================
        $ordersStatusData = [
            'labels' => ['En attente', 'Prête', 'En préparation', 'Annulée'],
            'data' => [30, 25, 20, 10],
            'colors' => [
                'En attente' => '#ffc107',
                'Prête' => '#28a745',
                'En préparation' => '#17a2b8',
                'Annulée' => '#dc3545'
            ]
        ];

        // =========================
        // Données récentes
        // =========================
        $recentReservations = [
            [
                'client' => 'Jean Dupont',
                'date' => '15/06/2023',
                'heure' => '19:30',
                'statut' => 'confirmée'
            ],
            [
                'client' => 'Marie Martin',
                'date' => '15/06/2023',
                'heure' => '20:00',
                'statut' => 'en attente'
            ],
            [
                'client' => 'Pierre Bernard',
                'date' => '16/06/2023',
                'heure' => '12:30',
                'statut' => 'confirmée'
            ],
            [
                'client' => 'Sophie Leroy',
                'date' => '16/06/2023',
                'heure' => '13:00',
                'statut' => 'annulée'
            ],
            [
                'client' => 'Thomas Petit',
                'date' => '16/06/2023',
                'heure' => '19:00',
                'statut' => 'confirmée'
            ]
        ];

        $recentOrders = [
            [
                'id' => 'CMD-00123',
                'client' => 'Jean Dupont',
                'total' => 67.50,
                'statut' => 'servie'
            ],
            [
                'id' => 'CMD-00124',
                'client' => 'Marie Martin',
                'total' => 42.00,
                'statut' => 'en préparation'
            ],
            [
                'id' => 'CMD-00125',
                'client' => 'Pierre Bernard',
                'total' => 89.75,
                'statut' => 'prête'
            ],
            [
                'id' => 'CMD-00126',
                'client' => 'Sophie Leroy',
                'total' => 34.50,
                'statut' => 'servie'
            ],
            [
                'id' => 'CMD-00127',
                'client' => 'Thomas Petit',
                'total' => 56.25,
                'statut' => 'en attente'
            ]
        ];

        return $this->render('admin/index.html.twig', [
            'stats' => $stats,
            'reservations_data' => $reservationsData,
            'orders_status_data' => $ordersStatusData,
            'recent_reservations' => $recentReservations,
            'recent_orders' => $recentOrders,
            'current_user' => $this->getUser(),
        ]);
    }
}
