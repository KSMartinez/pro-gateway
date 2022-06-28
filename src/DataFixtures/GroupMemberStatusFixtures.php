<?php

namespace App\DataFixtures;

use App\Entity\GroupMemberStatus;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class GroupMemberStatusFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $groupMemberStatuses = [
            GroupMemberStatus::REFUSE,
            GroupMemberStatus::ACTIF,
            GroupMemberStatus::INACTIF,
            GroupMemberStatus::INVITE,
            GroupMemberStatus::DEMANDE,
            GroupMemberStatus::IGNORE
        ];

        foreach ($groupMemberStatuses as $groupMemberStatus) {

            $groupMemberStatusObj = new GroupMemberStatus();
            $groupMemberStatusObj->setStatus($groupMemberStatus);
            $manager->persist($groupMemberStatusObj);
        }


        $manager->flush();
    }
}