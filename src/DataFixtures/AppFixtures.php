<?php

namespace App\DataFixtures;

use App\Entity\Inscription;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create('fr_FR');

        for ($i = 0; $i < 100; $i++) {
            $inscription = new Inscription();
            $inscription->setNom($faker->lastName);
            $inscription->setPrenoms($faker->firstName);
            $inscription->setEmail($faker->email);
            $inscription->setTelephone($faker->phoneNumber);
            $inscription->setDateInscription($faker->dateTimeBetween('-2 years', 'now'));
            $manager->persist($inscription);
        }

        $manager->flush();
    }
}
