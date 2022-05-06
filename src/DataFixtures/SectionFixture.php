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
        // $product = new Product();
        // $manager->persist($product);
        $faker = Factory::create();
        for ($i = 0; $i < 50; $i++) {
            $section = new Section();
            $section->setDesignation($faker->text(30));
            //
            for ($j = 0; $j < random_int(1, 25); $j++) {
                $etudiant = new Etudiant();
                $etudiant->setNom($faker->lastName);
                $etudiant->setPrenom($faker->firstName);
                $section->addEtudiant($etudiant);
                $etudiant->setSection($section);
                //persister l'étudiant
                $manager->persist($etudiant);
            }
            $manager->persist($section);
        }
        for ($i=0; $i < 50; $i++) {
            $etudiant = new Etudiant();
            $etudiant->setNom($faker->lastName);
            $etudiant->setPrenom($faker->firstName);
            $manager->persist($etudiant);
        }
        $manager->flush();
    }

}
