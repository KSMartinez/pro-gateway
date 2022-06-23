<?php

namespace App\DataFixtures;

use App\Entity\LevelOfEducation;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

/**
 *
 */
class LevelOfEducationFixture extends Fixture
{

    /**
     * @param ObjectManager $manager
     * @return void
     */
    public function load(ObjectManager $manager)
    {
        $levelsOfEducation = [
            LevelOfEducation::INDIFFERENT,
            LevelOfEducation::BAC,
            LevelOfEducation::BAC1,
            LevelOfEducation::BAC2,
            LevelOfEducation::BAC3,
            LevelOfEducation::BAC4,
            LevelOfEducation::BAC5,
            LevelOfEducation::ABOVE_BAC5

        ];

        foreach ($levelsOfEducation as $levelOfEducation) {

            $levelOfEducationObj = new LevelOfEducation();
            $levelOfEducationObj->setLabel($levelOfEducation);
            $manager->persist($levelOfEducationObj);
        }


        $manager->flush();
    }
}