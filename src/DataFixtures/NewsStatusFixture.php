<?php

namespace App\DataFixtures;

use App\Entity\NewsStatus;
use App\Entity\OfferStatus;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class NewsStatusFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $newsStatuses = [
            NewsStatus::ATTENTE,
            NewsStatus::PUBLIE,
            NewsStatus::BROUILLON
        ];

        foreach ($newsStatuses as $newsStatus) {

            $newsStatusObj = new NewsStatus();
            $newsStatusObj->setLabel($newsStatus);
            $manager->persist($newsStatusObj);
        }


        $manager->flush();
    }
}