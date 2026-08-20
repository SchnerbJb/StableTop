<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Mecanique;
use App\Entity\Categorie;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);
        $mecaniques = [
            'Stop ou Encore',
            'Deck Building',
            'Board Building',
            'Draft',
            'Engine Building',
            'Placement d\'ouvrier',
            'Gestion de ressource',
            'Placement de tuile',
            'Roll&Wright',
            'Dextérité',
            'Création de reseau',
            'Bluff',
            'Mouvement caché',
            'Rôle caché',
            'Contrôle de territoire',
            'Alignement',
            'Compter Capturer',
            'Mémoire',
            'Plis',
            'Lettres',
            'Histoire',
            'Asymétrique',
            'course',
            'Majorité',
            'Enchères',
            'Combo',
            'Communication'
        ];

          foreach ($mecaniques as $nom) {
            $mecanique = new Mecanique();
            $mecanique->setNom($nom);

            $manager->persist($mecanique);
        }

    
        $categories = [
            'Pour enfant',
            'Familial',
            'Soirée',
            'Avertis',
            'Expert'
        ];
        foreach ($categories as $nom) {
            $categorie = new Categorie();
            $categorie->setNom($nom);

            $manager->persist($categorie);

    }
        $manager->flush();
    }
}
