<?php

namespace App\DataFixtures;

use App\Entity\Animal;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AnimalFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $types = ['chien', 'cheval', 'brebis', 'cochon'];
        $races = ['labrador', 'frison', 'pottok', 'irish cob', 'mérinos', 'solognotes'];

        for ($i = 1; $i <= 50; $i++) {
            $animal = new Animal();
            $animal->setName('Animal ' . $i);
            $animal->setAge(rand(1, 10));
            $animal->setType($types[array_rand($types)]);
            $animal->setRace($races[array_rand($races)]);
            $animal->setDescription('Description for Animal ' . $i);
            $animal->setPrice(rand(100, 1000));
            $animal->setPhotos(['la-ferme.jpg']);
            $animal->setStatus('en vente');

            $manager->persist($animal);
        }

        $manager->flush();
    }
}
