<?php

namespace App\DataFixtures;

use App\Entity\EducationSpeciality;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class EducationSpecialityFixture extends Fixture
{

    /**
     * @param ObjectManager $manager
     * @return void
     */
    public function load(ObjectManager $manager)
    {
        $educationSpecialities = [
            EducationSpeciality::SPECIALITY1,
            EducationSpeciality::SPECIALITY2,
            EducationSpeciality::SPECIALITY3,
            EducationSpeciality::SPECIALITY4,

        ];

        foreach ($educationSpecialities as $educationSpeciality) {

            $domainObj = new EducationSpeciality();
            $domainObj->setLabel($educationSpeciality);
            $manager->persist($domainObj);
        }


        $manager->flush();
    }
}