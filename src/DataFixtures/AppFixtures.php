<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Categorie;
use App\Entity\Plat;
use App\Entity\RestaurantTable;
use App\Entity\Reservation;
use App\Entity\Commande;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        /* =======================================================
         * 1) Création ADMIN
         * ======================================================= */
        $admin = new User();
        $admin->setEmail("admin@yummiapp.com");
        $admin->setPassword($this->passwordHasher->hashPassword($admin, "admin123"));
        $admin->setNom("Admin");
        $admin->setPrenom("Yummi");
        $admin->setTelephone("00000000");
        $admin->setRole("ROLE_ADMIN");
        $admin->setRoles(["ROLE_ADMIN"]);
        $manager->persist($admin);

        /* =======================================================
         * 2) Catégories
         * ======================================================= */
        $categoriesNames = ["Healthy", "Vegan", "Salades", "Soupes", "Fitness"];
        $categories = [];

        foreach ($categoriesNames as $name) {
            $cat = new Categorie();
            $cat->setNom($name);
            $manager->persist($cat);
            $categories[] = $cat;
        }

        /* =======================================================
         * 3) Plats
         * ======================================================= */
        $platsData = [
            ["Salade César Healthy", "Poulet, laitue, parmesan, sauce légère", 18.500, "salade_cesar.jpg"],
            ["Smoothie Vert Detox", "Épinard, kiwi, banane", 12.000, "smoothie_vert.jpg"],
            ["Soupe aux légumes Bio", "Carottes, céleri, courgettes", 14.000, "soupe_legumes.jpg"],
            ["Power Bowl Vegan", "Quinoa, avocat, pois chiches", 22.000, "power_bowl.jpg"],
            ["Omelette Protéinée", "3 œufs, poulet, légumes", 15.000, "omelette_proteinee.jpg"],
        ];

        $plats = [];

        foreach ($platsData as $i => $p) {
            $plat = new Plat();
            $plat->setNom($p[0]);
            $plat->setDescription($p[1]);
            $plat->setPrix($p[2]);
            $plat->setImage($p[3]);
            $plat->setCategorie($categories[array_rand($categories)]);
            $manager->persist($plat);
            $plats[] = $plat;
        }

        /* =======================================================
         * 4) Tables du restaurant
         * ======================================================= */
        $tables = [];

        for ($i = 1; $i <= 10; $i++) {
            $table = new RestaurantTable();
            $table->setNumeroTable($i);
            $table->setCapacite(rand(2, 6));
            $table->setEmplacement("Zone " . chr(64 + $i)); // A, B, C...
            $table->setEtat("Libre");
            $manager->persist($table);
            $tables[] = $table;
        }

        /* =======================================================
         * 5) Création de clients
         * ======================================================= */
        $clients = [];

        for ($i = 1; $i <= 5; $i++) {
            $client = new User();
            $client->setEmail("client$i@yummiapp.com");
            $client->setPassword($this->passwordHasher->hashPassword($client, "client123"));
            $client->setNom("Client$i");
            $client->setPrenom("User$i");
            $client->setTelephone("55555$i");
            $client->setRole("ROLE_USER");
            $client->setRoles(["ROLE_USER"]);

            $manager->persist($client);
            $clients[] = $client;
        }

        /* =======================================================
         * 6) Réservations
         * ======================================================= */
        foreach ($clients as $client) {
            $reservation = new Reservation();
            $reservation->setDateReservation(new \DateTime("+1 day"));
            $reservation->setHeure("19:00");
            $reservation->setNombrePersonne(rand(2, 5));
            $reservation->setStatut("Confirmée");
            $reservation->setUser($client);

            // Choisir une table libre
            $reservation->setRestaurantTable(
                $tables[array_rand($tables)]
            );

            $manager->persist($reservation);
        }

        /* =======================================================
         * 7) Commandes
         * ======================================================= */
        foreach ($clients as $client) {
            $commande = new Commande();
            $commande->setDateCommande(new \DateTime());
            $commande->setStatut("Payée");
            $commande->setUser($client);
            $commande->setTotal(0);

            // Ajouter 2–3 plats
            $prixTotal = 0;

            foreach (array_rand($plats, 3) as $i) {
                $commande->addPlat($plats[$i]);
                $prixTotal += $plats[$i]->getPrix();
            }

            $commande->setTotal($prixTotal);
            $manager->persist($commande);
        }

        /* =======================================================
         * FLUSH FINAL
         * ======================================================= */
        $manager->flush();
    }
}
