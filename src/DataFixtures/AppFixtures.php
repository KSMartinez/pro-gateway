<?php

namespace App\DataFixtures;

use App\Entity\Experience;
use App\Entity\User;
use App\Factory\CVFactory;
use App\Factory\DomainFactory;
use App\Factory\ExperienceFactory;
use App\Factory\OfferFactory;
use App\Factory\TypeOfContractFactory;
use App\Factory\UserFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        DomainFactory::createMany(5);
        TypeOfContractFactory::createMany(8);
        OfferFactory::createMany(20);

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
        $manager->flush();
    }

    /**
     * @return string[]
     */
    public function getDependencies(): array
    {
        return [
            OfferStatusFixture::class,
            GroupMemberStatusFixtures::class
        ];
    }
}
