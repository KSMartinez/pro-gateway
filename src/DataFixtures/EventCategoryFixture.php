<?php

namespace App\DataFixtures;

use App\Entity\EventCategory;
use App\Entity\NewsCategory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use function Zenstruck\Foundry\factory;
use function Zenstruck\Foundry\faker;

class EventCategoryFixture extends Fixture
{

    /**
     * @param ObjectManager $manager
     * @return void
     */
    public function load(ObjectManager $manager)
    {
        $categories = [
            "Recherche",
            "Rencontre",
            "Exposition",
            "Conférence",
            "Séminaire",
            "Spectacle",
            "Soutenance",
            "Performance",
            "Stands",
            "Tribune",
            "Atelier",
            "Cérémonie",
            "Colloque",
            "Autre"
        ];

        foreach ($categories as $category) {
            factory(EventCategory::class)->create([
                'label' => $category
            ]);
        }
    }
}