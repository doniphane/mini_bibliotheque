<?php

namespace App\DataFixtures;

use App\Entity\Auteur;
use App\Entity\Livre;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // Liste d'auteurs
        $auteurs = [];

        for ($i = 1; $i <= 10; $i++) {
            $auteur = new Auteur();
            $auteur->setPrenom('Prénom' . $i);
            $auteur->setNom('Nom' . $i);
            $manager->persist($auteur);
            $auteurs[] = $auteur;
        }

        // Liste de livres
        for ($i = 1; $i <= 30; $i++) {
            $livre = new Livre();
            $livre->setTitre('Livre ' . $i);
            $livre->setAnnee(mt_rand(1800, 2025));
            $livre->setAuteur($auteurs[array_rand($auteurs)]);
            $manager->persist($livre);
        }

        $manager->flush();
    }
}
