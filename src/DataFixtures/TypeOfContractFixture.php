<?php

namespace App\DataFixtures;

use App\Entity\TypeOfContract;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

/**
 *
 */
class TypeOfContractFixture extends Fixture
{
    /**
     * @param ObjectManager $manager
     * @return void
     */
    public function load(ObjectManager $manager): void
    {
        $typeOfContracts = [
            TypeOfContract::CDI,
            TypeOfContract::CDD,
            TypeOfContract::CIFRE,
            TypeOfContract::INDIFFERENT,
            TypeOfContract::INTERIM,
            TypeOfContract::POST_DOCTORANT,
            TypeOfContract::SERVICE_CIVIQUE,
            TypeOfContract::VIE_VIA
        ];

        foreach ($typeOfContracts as $typeOfContract) {

            $typeOfContractObj = new TypeOfContract();
            $typeOfContractObj->setType($typeOfContract);
            $manager->persist($typeOfContractObj);
        }


        $manager->flush();
    }
}