<?php

namespace App\Tests\Service;

use App\Entity\GroupMember;
use App\Entity\GroupMemberStatus;
use App\Factory\GroupFactory;
use App\Factory\UserFactory;
use App\Service\GroupMemberService;
use Doctrine\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use function PHPUnit\Framework\assertEquals;
use function Zenstruck\Foundry\factory;

class GroupMemberServiceTest extends WebTestCase
{

    /**
     * @var ObjectManager
     */
    private ObjectManager $entityManager;
    /**
     * @var ContainerInterface
     */
    private ContainerInterface $c;

    private KernelBrowser $client;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        //self::bootKernel();
        $this->client = static::createClient();
        $this->c = self::$kernel->getContainer();
        $this->entityManager = $this->c->get('doctrine')
                                       ->getManager();

    }

    public function testGetGroupMemberAdmins()
    {
        $groupMemberStatusFactory = factory(GroupMemberStatus::class);
        $statusActive = $groupMemberStatusFactory->findOrCreate(['status' => GroupMemberStatus::ACTIF]);

        $group = GroupFactory::random();
        factory(GroupMember::class)->create(
            [
                'user' => UserFactory::random(),
                'groupOfMember' => $group,
                'groupMemberStatus' => $statusActive,
                'memberRoles' => [GroupMember::ROLE_GROUP_ADMIN]
            ]);

        factory(GroupMember::class)->create(
            [
                'user' => UserFactory::random(),
                'groupOfMember' => $group,
                'groupMemberStatus' => $statusActive,
                'memberRoles' => [GroupMember::ROLE_GROUP_ADMIN]
            ]);

        factory(GroupMember::class)->create(
            [
                'user' => UserFactory::random(),
                'groupOfMember' => $group,
                'groupMemberStatus' => $statusActive,
                'memberRoles' => [GroupMember::ROLE_GROUP_USER]
            ]);

        $service = $this->c->get(GroupMemberService::class);
        $members = $service->getGroupMemberAdmins($group->object());

        assertEquals(2, count($members));
    }

    public function testAcceptRequestToJoin()
    {
        $groupMemberStatusFactory = factory(GroupMemberStatus::class);
        $groupMemberFactory = factory(GroupMember::class);
        $statusActif = $groupMemberStatusFactory->findOrCreate(['status' => GroupMemberStatus::ACTIF]);
        $statusDemande = $groupMemberStatusFactory->findOrCreate(['status' => GroupMemberStatus::DEMANDE]);

        $group = GroupFactory::random();


        $groupAdminUser = UserFactory::random();

        //groupAdmin
        $groupMemberFactory->create(
            [
                'user' => UserFactory::random(),
                'groupOfMember' => $group,
                'groupMemberStatus' => $statusActif,
                'memberRoles' => [GroupMember::ROLE_GROUP_ADMIN]
            ]);

        $memberUser = UserFactory::random();

        //member who has requested to join
        $groupMemberRequestingToJoin = $groupMemberFactory->create(
            [
                'user' => UserFactory::random(),
                'groupOfMember' => $group,
                'groupMemberStatus' => $statusDemande
            ]);

        //logging in the groupAdmin
        $this->client->loginUser($groupAdminUser->object());


        $service = $this->c->get(GroupMemberService::class);

        $groupMemberRequestingToJoin = $this->entityManager->getRepository(GroupMember::class)->find($groupMemberRequestingToJoin->object());

        $service->acceptRequestToJoin($groupMemberRequestingToJoin);

        $groupMember = $groupMemberFactory->repository()->find($groupMemberRequestingToJoin);


        assertEquals(GroupMemberStatus::ACTIF, $groupMember->object()->getGroupMemberStatus());

    }
/*
    public function testCreateInvitationForGroupMember()
    {

    }

    public function testAcceptInvite()
    {

    }

    public function testRemoveUserFromGroup()
    {

    }

    public function testIgnoreRequestToJoinGroup()
    {

    }

    public function testMakeMemberGroupAdmin()
    {

    }

    public function testRefuseInvite()
    {

    }

    public function testRequestToJoinGroup()
    {

    }

    public function testRemoveAdminRoleFromMember()
    {

    }

    public function testIsGroupAdmin()
    {

    }*/
}
