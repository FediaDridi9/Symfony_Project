<?php

namespace App\Controller\Admin;

use App\Entity\Plat;
use App\Entity\Reservation;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    public function __construct(private EntityManagerInterface $em) {}

    #[Route('/admin', name: 'easyadmin')]
    public function index(): Response
    {
        // === Stats pour les 4 cartes ===
        $reservationsEnAttente = $this->em->getRepository(Reservation::class)->count(['statut' => 'en_attente']);
        $reservationsConfirmees = $this->em->getRepository(Reservation::class)->count(['statut' => 'confirmée']);
        $nbPlats = $this->em->getRepository(Plat::class)->count([]);
        $nbClients = $this->em->getRepository(User::class)->count(['role' => 'client']);

        // === Stats pour le graphique circulaire : réservations par jour de la semaine ===
        $reservations = $this->em->getRepository(Reservation::class)->findAll();

        $counts = [1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0, 6 => 0, 7 => 0]; // 1 = Lundi, 7 = Dimanche

        foreach ($reservations as $reservation) {
            if ($reservation->getDate()) {
                $day = (int)$reservation->getDate()->format('N'); // 1=Lundi à 7=Dimanche
                $counts[$day]++;
            }
        }

        $labels = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'];
        $data = [$counts[1], $counts[2], $counts[3], $counts[4], $counts[5], $counts[6], $counts[7]];

        return $this->render('admin/dashboard.html.twig', [
            'en_attente' => $reservationsEnAttente,
            'confirmees' => $reservationsConfirmees,
            'plats' => $nbPlats,
            'clients' => $nbClients,

            // === CES DEUX LIGNES ÉTAIENT MANQUANTES ! ===
            'reservations_par_jour_labels' => $labels,
            'reservations_par_jour_data' => $data,
        ]);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()->setTitle('Restaurant - Administration');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToCrud('Catégories', 'fas fa-list', \App\Entity\Categorie::class);
        yield MenuItem::linkToCrud('Plats', 'fas fa-utensils', Plat::class);
        yield MenuItem::linkToCrud('Réservations', 'fas fa-calendar-check', Reservation::class);
        yield MenuItem::linkToCrud('Utilisateurs', 'fas fa-users', User::class);
    }
}