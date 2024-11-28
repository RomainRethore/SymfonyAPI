<?php

namespace App\DataFixtures;

use App\Entity\Drawing;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {

        for ($i = 0; $i < 20; $i++) {
            $drawing = new Drawing();
            $drawing->setTitle('title' . $i);
            $drawing->setAuthor('author' . $i);
            $drawing->setImage('image' . $i);
            $manager->persist($drawing);
        }

        $manager->flush();
    }
}
