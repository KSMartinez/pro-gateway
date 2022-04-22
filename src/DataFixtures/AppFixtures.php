<?php

namespace App\DataFixtures;

use App\Factory\CVFactory;
use App\Factory\DomainFactory;
use App\Factory\OfferFactory;
use App\Factory\TypeOfContractFactory;
use App\Factory\UserFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        DomainFactory::createMany(5);
        TypeOfContractFactory::createMany(8);
        OfferFactory::createMany(20);

        $users = UserFactory::createMany(10);

        foreach ($users as $user){
            CVFactory::new(['user' => $user]);
        }




        $manager->flush();
    }
}
