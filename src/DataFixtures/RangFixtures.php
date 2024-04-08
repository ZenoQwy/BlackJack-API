<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;
use App\Entity\Rang;

class RangFixtures extends Fixture
{
    private $faker;

    public function __construct(){
        $this->faker = Factory::create("fr_FR");
    }

    public function load(ObjectManager $manager): void
    {
        $scoremin = 0;
        $scoremax = 100;

        for ($i=0;$i<10;$i++){
            $rang = new Rang();
            $rang->setLibelle($this->faker->lastName());
            if ($i==0) {
                $rang->setScoreMin($scoremin);
                $rang->setScoreMax($scoremax);
            } else {
                $rang->setScoreMin($scoremin+1);
                $rang->setScoreMax($scoremax);
            }
            $scoremin += 100;
            $scoremax += 100; 
            $manager->persist($rang);
        }
        $manager->flush();
        
    }
}