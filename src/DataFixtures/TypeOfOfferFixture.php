<?php

namespace App\DataFixtures;

use App\Entity\TypeOfOffer;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class TypeOfOfferFixture extends Fixture
{

    /**
     * @param ObjectManager $manager
     * @return void
     */
    public function load(ObjectManager $manager)
    {
        $typeOfOffers = [
            TypeOfOffer::APPRENTISSAGE,
            TypeOfOffer::EMPLOI_CONFIRME,
            TypeOfOffer::EMPLOI_DEBUTANT,
            TypeOfOffer::EMPLOI_ETUDIANT,
            TypeOfOffer::STAGE,

        ];

        foreach ($typeOfOffers as $typeOfOffer) {

            $domainObj = new TypeOfOffer();
            $domainObj->setType($typeOfOffer);
            $manager->persist($domainObj);
        }


        $manager->flush();
    }
}