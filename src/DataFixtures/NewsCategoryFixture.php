<?php

namespace App\DataFixtures;

use App\Entity\NewsCategory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use function Zenstruck\Foundry\factory;
use function Zenstruck\Foundry\faker;

class NewsCategoryFixture extends Fixture
{

    /**
     * @param ObjectManager $manager
     * @return void
     */
    public function load(ObjectManager $manager)
    {
        //create 5 new fixtures
        for ($i = 0; $i < 5; $i++) {
            factory(NewsCategory::class)
                ->create(['title' => faker()->colorName()]);
        }
    }
}