<?php

namespace App\DataFixtures;

use App\Entity\EventStatus;
use App\Entity\OfferStatus;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class EventStatusFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $eventStatuses = [
            EventStatus::EN_ATTENTE,
            EventStatus::PUBLIE,
            EventStatus::BROUILLON, 
            EventStatus::CLOTURE
        ];

        foreach ($eventStatuses as $eventStatus) {

            $eventStatusObj = new EventStatus();
            $eventStatusObj->setLabel($eventStatus);
            $manager->persist($eventStatusObj);
        }


        $manager->flush();
    }
}