<?php

namespace App\DataFixtures;

use App\Entity\MessageStatus;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class MessageStatusFixture extends Fixture
{

    /**
     * @param ObjectManager $manager
     * @return void
     */
    public function load(ObjectManager $manager)
    {
        $messageStatuses = [
            MessageStatus::NEW,
            MessageStatus::OPEN
        ];

        foreach ($messageStatuses as $messageStatus) {

            $messageStatusObj = new MessageStatus();
            $messageStatusObj->setStatus($messageStatus);
            $manager->persist($messageStatusObj);
        }


        $manager->flush();
    }
}