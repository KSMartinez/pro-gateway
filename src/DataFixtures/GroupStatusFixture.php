<?php

namespace App\DataFixtures;

use App\Entity\GroupStatus;
use App\Entity\OfferStatus;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class GroupStatusFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $groupStatuses = [
            GroupStatus::EN_ATTENTE,
            GroupStatus::CONFIRME,
            GroupStatus::CONFIRME
        ];

        foreach ($groupStatuses as $groupStatus) {

            $groupStatusObj = new GroupStatus();
            $groupStatusObj->setStatus($groupStatus);
            $manager->persist($groupStatusObj);
        }


        $manager->flush();
    }
}