<?php

namespace App\DataFixtures;

use App\Factory\DomainFactory;
use App\Factory\OfferFactory;
use App\Factory\TypeOfContractFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        DomainFactory::createMany(5);
        TypeOfContractFactory::createMany(8);
        OfferFactory::createMany(20);

        $manager->flush();
    }
}
