<?php

namespace App\DataFixtures;

use App\Entity\GroupCategory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class GroupCategoryFixture extends Fixture
{

    /**
     * @param ObjectManager $manager
     * @return void
     */
    public function load(ObjectManager $manager)
    {
        $groupCategories = [
            GroupCategory::SPORT,
            GroupCategory::INTERNATIONAL,
            GroupCategory::CULTURE,
            GroupCategory::AFTER_WORK,

        ];

        foreach ($groupCategories as $groupCategory) {

            $domainObj = new GroupCategory();
            $domainObj->setLabel($groupCategory);
            $manager->persist($domainObj);
        }


        $manager->flush();
    }
}