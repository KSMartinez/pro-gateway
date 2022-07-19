<?php

namespace App\DataFixtures;

use App\Entity\OfferCategory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class OfferCategoryFixture extends Fixture
{

    /**
     * @param ObjectManager $manager
     * @return void
     */
    public function load(ObjectManager $manager)
    {
        $offerCategories = [
            OfferCategory::ALTERNANCE,
            OfferCategory::VIE_VIA,
            OfferCategory::EMPLOI_ETUDIANT,
            OfferCategory::EMPLOI,
            OfferCategory::STAGE,
            OfferCategory::SERVICE_CIVIQUE

        ];

        foreach ($offerCategories as $offerCategory) {

            $domainObj = new OfferCategory();
            $domainObj->setLabel($offerCategory);
            $manager->persist($domainObj);
        }


        $manager->flush();
    }
}