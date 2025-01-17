<?php

namespace App\DataFixtures;

use App\Entity\OfferStatus;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class OfferStatusFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $offerStatuses = [
            OfferStatus::PUBLIEE,
            OfferStatus::ATTENTE_DE_VALIDATION,
            OfferStatus::POURVUE,
            OfferStatus::SUPPRIMEE,
            OfferStatus::ARCHIVEE,
            OfferStatus::REFUSE
        ];

        foreach ($offerStatuses as $offerStatus) {

            $offerStatusObj = new OfferStatus();
            $offerStatusObj->setLabel($offerStatus);
            $manager->persist($offerStatusObj);
        }


        $manager->flush();
    }
}
