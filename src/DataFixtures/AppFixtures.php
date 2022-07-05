<?php

namespace App\DataFixtures;

use App\Entity\Domain;
use App\Entity\SectorOfOffer;
use App\Entity\User;
use App\Factory\AdviceFactory;
use App\Factory\CVFactory;
use App\Factory\DomainFactory;
use App\Factory\EventFactory;
use App\Factory\ExperienceFactory;
use App\Factory\GroupFactory;
use App\Factory\NewsFactory;
use App\Factory\OfferFactory;
use App\Factory\UserFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use function Zenstruck\Foundry\faker;

class AppFixtures extends Fixture implements DependentFixtureInterface
{
    /**
     * @param ObjectManager $manager
     * @return void
     */
    public function load(ObjectManager $manager): void
    {

        OfferFactory::createMany(20);

        /**
         * This is the main fake user
         */
        UserFactory::createOne(['firstname' => 'Fake', 'surname' => 'User', 'email' => 'fakeuser@email.com']);

        /** @var User[] $users */
        $users = UserFactory::createMany(10);

        //set Roles
        $users[0]->setRoles([User::ROLE_ADMIN]);
        $users[1]->setRoles([User::ROLE_ALUMNI]);
        $users[2]->setRoles([User::ROLE_ENSEIGNANT]);
        $users[3]->setRoles([User::ROLE_ETUDIANT]);

        foreach ($users as $user){
            //$manager->persist($user);
            CVFactory::new(['user' => $user])->create();
        }

        ExperienceFactory::createMany(20);
        GroupFactory::createMany(5);


        EventFactory::createMany(10);
        NewsFactory::createMany(10);
        AdviceFactory::createMany(10);



        $manager->flush();
    }

    /**
     * @return string[]
     */
    public function getDependencies(): array
    {
        return [
            OfferStatusFixture::class,
            GroupMemberStatusFixtures::class,
            GroupStatusFixture::class,
            TypeOfContractFixture::class,
            DomainFixture::class,
            TypeOfOfferFixture::class,
            SectorOfOfferFixture::class,
            LevelOfEducationFixture::class,
            EventCategoryFixture::class,
            NewsCategoryFixture::class
        ];
    }
}
