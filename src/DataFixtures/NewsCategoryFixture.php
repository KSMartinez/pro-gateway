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
        $categories = [
            "Recrutement",
            "Distinction",
            "Étudiant",
            "Formation",
            "Gouvernance",
            "Hommage",
            "International",
            "Project",
            "Pratique",
            "Recherche",
            "Regard sur",
            "Autre"
        ];

        foreach ($categories as $category) {
            factory(NewsCategory::class)->create([
                'label' => $category
            ]);
        }
    }
}