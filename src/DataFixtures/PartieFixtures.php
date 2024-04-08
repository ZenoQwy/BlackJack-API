<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;
use App\Entity\Joueur;
use App\Entity\Partie;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class PartieFixtures extends Fixture implements DependentFixtureInterface
{
    private $faker;

    public function __construct(UserPasswordHasherInterface $passwordHasher){
        $this->faker = Factory::create("fr_FR");
    }

    public function load(ObjectManager $manager): void
    {
        for ($i=0;$i<10;$i++){

            $partie = new Partie();
            $partie->setDateDebut($this->faker->dateTimeBetween('-6 months', '+6 months'));
            $partie->setDateFin($this->faker->dateTimeBetween('+6 months', '+12 months'));
            $partie->setEtat($this->faker->randomElement(['En cours', 'Terminé']));
            if($partie->getEtat() == 'Terminé' ){
                $partie->setJoueurGagne($this->faker->randomDigit(0, 1));
            }
            $partie->setPointJoueur($this->faker->randomDigit(4, 24));
            $partie->setPointBanquier($this->faker->randomDigit(4, 24) );
            $partie->setJoueur($this->getReference('joueur'.mt_rand(0,9)));
            $manager->persist($partie);
        }
        $manager->flush();
    }
    public function getDependencies(){
        return [JoueurFixtures::class];
    }
}