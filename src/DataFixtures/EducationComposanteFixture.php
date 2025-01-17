<?php

namespace App\DataFixtures;

use App\Entity\EducationComposante;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class EducationComposanteFixture extends Fixture
{

    /**
     * @param ObjectManager $manager
     * @return void
     */
    public function load(ObjectManager $manager)
    {
        $educationComposantes = [
            EducationComposante::COMPOSANTE1,
            EducationComposante::COMPOSANTE2,
            EducationComposante::COMPOSANTE3,
            EducationComposante::COMPOSANTE4,

        ];

        foreach ($educationComposantes as $educationComposante) {

            $domainObj = new EducationComposante();
            $domainObj->setLabel($educationComposante);
            $manager->persist($domainObj);
        }


        $manager->flush();
    }
}