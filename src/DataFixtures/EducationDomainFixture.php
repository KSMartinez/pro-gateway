<?php

namespace App\DataFixtures;

use App\Entity\EducationDomain;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class EducationDomainFixture extends Fixture
{

    /**
     * @param ObjectManager $manager
     * @return void
     */
    public function load(ObjectManager $manager)
    {
        $educationDomains = [
            EducationDomain::DOMAIN1,
            EducationDomain::DOMAIN2,
            EducationDomain::DOMAIN3,
            EducationDomain::DOMAIN4,

        ];

        foreach ($educationDomains as $educationDomain) {

            $domainObj = new EducationDomain();
            $domainObj->setLabel($educationDomain);
            $manager->persist($domainObj);
        }


        $manager->flush();
    }
}