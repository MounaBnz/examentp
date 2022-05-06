<?php

namespace App\DataFixtures;

use App\Entity\Etudiant;
use App\Entity\Section;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class SectionFixture extends Fixture
{
    /**
     * @throws \Exception
     */
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();
        //Remplir la table Etudiant
        for ($i=0; $i < 50; $i++) {
            $etudiant = new Etudiant();
            $etudiant->setNom($faker->lastName);
            $etudiant->setPrenom($faker->firstName);
            $manager->persist($etudiant);
        }
        $manager->flush();
        // $product = new Product();
        // $manager->persist($product);
        for ($i = 0; $i < 50; $i++) {
            $section = new Section();
            //Un texte avec un nombre maximum de 30 chars
            $section->setDesignation($faker->text(30));
            //
            for ($j = 0; $j < random_int(1, 25); $j++) {
                $etudiant = new Etudiant();
//                Génerer les noms et prénoms des étudiants
                $etudiant->setNom($faker->lastName);
                $etudiant->setPrenom($faker->firstName);
                //se profiter de la méthode addEtudiant et setSection dans les entités (resp) Section et Etudiant
                $section->addEtudiant($etudiant);
                $etudiant->setSection($section);
                //persister l'étudiant
                $manager->persist($etudiant);
            }
            $manager->persist($section);
        }

    }

}
