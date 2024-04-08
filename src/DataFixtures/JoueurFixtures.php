<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;
use App\Entity\Joueur;
use App\Entity\Rang;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class JoueurFixtures extends Fixture implements DependentFixtureInterface
{
    private $faker;
    private $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher){
        $this->passwordHasher = $passwordHasher;
        $this->faker = Factory::create("fr_FR");
    }
    public function load(ObjectManager $manager): void
    {
        for ($i=0;$i<10;$i++){
            $prenom = $this->faker->LastName();
            $joueur = new Joueur();
            $joueur->setPseudonyme($this->faker->firstName());
            $joueur->setPointElo(0);
            $joueur->setEmail(strtolower($prenom).'.'.strtolower($prenom).'@'.$this->faker->freeEmailDomain());
            $joueur->setPassword($this->passwordHasher->hashPassword($joueur,strtolower($prenom)));
            $joueur->setDateInscription($this->faker->dateTimeThisYear());
            $joueur->setNbrWins($this->faker->randomDigit(0, 50));
            $joueur->setTotalParties($joueur->getNbrWins()+$this->faker->randomDigit(0, 20));
            $this->addReference('joueur'.$i,$joueur);
            $manager->persist($joueur);
        }
        $manager->flush();
    }
    public function getDependencies(){
        return [RangFixtures::class];
    }
}