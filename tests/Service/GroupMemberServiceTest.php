<?php

namespace App\Tests\Service;

use App\Entity\Group;
use App\Entity\GroupMember;
use App\Entity\GroupMemberStatus;
use App\Entity\User;
use App\Factory\GroupFactory;
use App\Factory\UserFactory;
use App\Service\GroupMemberService;
use Doctrine\Persistence\ObjectManager;
use Exception;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Zenstruck\Foundry\Proxy;
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

    private GroupMemberService $service;

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
        $this->service = $this->c->get(GroupMemberService::class);

    }

    public function testGetGroupMemberAdmins()
    {

        //init
        $group = GroupFactory::random();

        //groupAdmin
        $this->createNewGroupMemberWith(UserFactory::random(), $group, GroupMemberStatus::ACTIF,
                                        [GroupMember::ROLE_GROUP_ADMIN]);
        $this->createNewGroupMemberWith(UserFactory::random(), $group, GroupMemberStatus::ACTIF,
                                        [GroupMember::ROLE_GROUP_ADMIN]);
        $this->createNewGroupMemberWith(UserFactory::random(), $group, GroupMemberStatus::ACTIF,
                                        [GroupMember::ROLE_GROUP_USER]);



        //test
        $members = $this->service->getGroupMemberAdmins($group->object());

        //assert
        assertEquals(2, count($members));
    }

    /**
     * @throws Exception
     */
    public function testAcceptRequestToJoin()
    {

        //init
        $group = GroupFactory::random();

        //groupAdmin
        $groupAdminUser = UserFactory::random();
        $groupAdminMember = $this->createNewGroupMemberWith($groupAdminUser, $group, GroupMemberStatus::ACTIF,
                                                            [GroupMember::ROLE_GROUP_ADMIN]);


        //member who has requested to join
        $memberUser = UserFactory::random();
        $groupMemberRequestingToJoin = $this->createNewGroupMemberWith($memberUser, $group, GroupMemberStatus::DEMANDE,
                                                                       []);

        //test
        $groupMemberRequestingToJoin = $this->entityManager->getRepository(GroupMember::class)
                                                           ->find($groupMemberRequestingToJoin->object());
        $this->service->acceptRequestToJoin($groupMemberRequestingToJoin);

        //assert
        $groupMember = factory(GroupMember::class)
            ->repository()
            ->find($groupMemberRequestingToJoin);
        assertEquals(GroupMemberStatus::ACTIF, $groupMember->object()
                                                           ->getGroupMemberStatus());

    }


    /**
     * @throws Exception
     */
    public function testCreateInvitationForGroupMember()
    {
        //init
        $group = GroupFactory::random();

        //groupAdmin
        $groupAdminUser = UserFactory::random();
        $groupAdminMember = $this->createNewGroupMemberWith($groupAdminUser, $group, GroupMemberStatus::ACTIF,
                                                            [GroupMember::ROLE_GROUP_ADMIN]);


        //member who has requested to join
        $memberUser = UserFactory::random();
        $memberInvitedToJoin = $this->createNewGroupMemberWith($memberUser, $group, null, []);

        //test
        $memberInvitedToJoin = $this->entityManager->getRepository(GroupMember::class)
                                                   ->find($memberInvitedToJoin->object());

        $this->service->createInvitationForGroupMember($memberInvitedToJoin);

        //assert
        $groupMember = factory(GroupMember::class)
            ->repository()
            ->find($memberInvitedToJoin);
        assertEquals(GroupMemberStatus::INVITE, $groupMember->object()
                                                            ->getGroupMemberStatus());
    }


    /**
     * @throws Exception
     */
    public function testAcceptInvite()
    {
        //init
        $group = GroupFactory::random();

        //groupAdmin
        $groupAdminUser = UserFactory::random();
        $groupAdminMember = $this->createNewGroupMemberWith($groupAdminUser, $group, GroupMemberStatus::ACTIF,
                                                            [GroupMember::ROLE_GROUP_ADMIN]);


        //member who has requested to join
        $memberUser = UserFactory::random();
        $memberInvitedToJoin = $this->createNewGroupMemberWith($memberUser, $group, GroupMemberStatus::INVITE, []);


        //test when logged-in user is same as member
        $memberInvitedToJoin = $this->entityManager->getRepository(GroupMember::class)
                                                   ->find($memberInvitedToJoin->object());


        $this->service->acceptInvite($memberInvitedToJoin, $memberUser->object());

        //assert
        $groupMember = factory(GroupMember::class)
            ->repository()
            ->find($memberInvitedToJoin);
        assertEquals(GroupMemberStatus::ACTIF, $groupMember->object()
                                                           ->getGroupMemberStatus());
        self::assertContains(GroupMember::ROLE_GROUP_USER, $groupMember->object()
                                                                       ->getMemberRoles());

        //test and assert when logged-in user is not the same as member
        $randomUser = UserFactory::random();
        self::assertFalse($this->service->acceptInvite($memberInvitedToJoin, $randomUser->object()));

    }

    /**
     * @throws Exception
     */
    public function testRemoveUserFromGroup()
    {

        //init
        $group = GroupFactory::random();

        //groupAdmin
        $groupAdminUser = UserFactory::random();
        $groupAdminMember = $this->createNewGroupMemberWith($groupAdminUser, $group, GroupMemberStatus::ACTIF,
                                                            [GroupMember::ROLE_GROUP_ADMIN]);


        //member to remove
        $memberUser = UserFactory::random();
        $memberToRemove = $this->createNewGroupMemberWith($memberUser, $group, GroupMemberStatus::ACTIF,
                                                          [GroupMember::ROLE_GROUP_USER]);

        //test
        $memberToRemove = $this->entityManager->getRepository(GroupMember::class)
                                              ->find($memberToRemove->object());

        $this->service->removeUserFromGroup($memberToRemove, $memberUser->object());

        //assert
        self::assertEquals(GroupMemberStatus::INACTIF, $memberToRemove->getGroupMemberStatus());
        self::assertNotContains(GroupMember::ROLE_GROUP_USER, $memberToRemove->getMemberRoles());

        //test with removing admin
        $memberUser = UserFactory::random();
        $memberToRemove = $this->createNewGroupMemberWith($memberUser, $group, GroupMemberStatus::ACTIF,
                                                          [GroupMember::ROLE_GROUP_ADMIN]);

        $memberToRemove = $this->entityManager->getRepository(GroupMember::class)
                                              ->find($memberToRemove->object());

        //assert
        $this->expectException(Exception::class);
        $this->service->removeUserFromGroup($memberToRemove, $memberUser->object());


    }

    /**
     * @throws Exception
     */
    public function testIgnoreRequestToJoinGroup()
    {
        //init
        $group = GroupFactory::random();

        //groupAdmin
        $groupAdminUser = UserFactory::random();
        $groupAdminMember = $this->createNewGroupMemberWith($groupAdminUser, $group, GroupMemberStatus::ACTIF,
                                                            [GroupMember::ROLE_GROUP_ADMIN]);


        //member who has requested to join
        $memberUser = UserFactory::random();
        $memberRequestingToJoin = $this->createNewGroupMemberWith($memberUser, $group, GroupMemberStatus::DEMANDE, []);


        //test when logged-in user is same as member
        $memberRequestingToJoin = $this->entityManager->getRepository(GroupMember::class)
                                                      ->find($memberRequestingToJoin->object());


        $this->service->ignoreRequestToJoinGroup($memberRequestingToJoin);

        //assert
        $groupMember = factory(GroupMember::class)
            ->repository()
            ->find($memberRequestingToJoin);
        assertEquals(GroupMemberStatus::IGNORE, $groupMember->object()
                                                            ->getGroupMemberStatus());

    }

    public function testMakeMemberGroupAdmin()
    {
        //init
        $group = GroupFactory::random();

        //groupAdmin
        $groupAdminUser = UserFactory::random();
        $groupAdminMember = $this->createNewGroupMemberWith($groupAdminUser, $group, GroupMemberStatus::ACTIF,
                                                            [GroupMember::ROLE_GROUP_ADMIN]);


        //member who is not an admin
        $memberUser = UserFactory::random();
        $nonAdminMember = $this->createNewGroupMemberWith($memberUser, $group, GroupMemberStatus::ACTIF,
                                                          [GroupMember::ROLE_GROUP_USER]);


        //test
        $nonAdminMember = $this->entityManager->getRepository(GroupMember::class)
                                              ->find($nonAdminMember->object());


        $this->service->makeMemberGroupAdmin($nonAdminMember);

        //assert
        $groupMember = factory(GroupMember::class)
            ->repository()
            ->find($nonAdminMember);

        self::assertContains(GroupMember::ROLE_GROUP_ADMIN, $groupMember->object()
                                                                        ->getMemberRoles());

    }

    /**
     * @throws Exception
     */
    public function testRefuseInvite()
    {

        //init
        $group = GroupFactory::random();

        //groupAdmin
        $groupAdminUser = UserFactory::random();
        $groupAdminMember = $this->createNewGroupMemberWith($groupAdminUser, $group, GroupMemberStatus::ACTIF,
                                                            [GroupMember::ROLE_GROUP_ADMIN]);


        //member who has requested to join
        $memberUser = UserFactory::random();
        $memberInvitedToJoin = $this->createNewGroupMemberWith($memberUser, $group, GroupMemberStatus::INVITE, []);


        //test when logged-in user is same as member
        $memberInvitedToJoin = $this->entityManager->getRepository(GroupMember::class)
                                                   ->find($memberInvitedToJoin->object());


        $res = $this->service->refuseInvite($memberInvitedToJoin, $memberUser->object());

        //assert
        $groupMember = factory(GroupMember::class)
            ->repository()
            ->find($memberInvitedToJoin);
        assertEquals(GroupMemberStatus::REFUSE, $groupMember->object()
                                                            ->getGroupMemberStatus());

        //test and assert when logged-in user is not the same as member
        $randomUser = UserFactory::random();
        self::assertFalse($this->service->refuseInvite($memberInvitedToJoin, $randomUser->object()));
    }

    /**
     * @throws Exception
     */
    public function testRequestToJoinGroup()
    {
        //init
        $group = GroupFactory::random();

        //groupAdmin
        $groupAdminUser = UserFactory::random();
        $groupAdminMember = $this->createNewGroupMemberWith($groupAdminUser, $group, GroupMemberStatus::ACTIF,
                                                            [GroupMember::ROLE_GROUP_ADMIN]);


        //member who has requested to join
        $memberUser = UserFactory::random();
        $memberRequestingToJoin = $this->createNewGroupMemberWith($memberUser, $group, null, []);


        //test
        $memberRequestingToJoin = $this->entityManager->getRepository(GroupMember::class)
                                                      ->find($memberRequestingToJoin->object());


        $this->service->requestToJoinGroup($memberRequestingToJoin);

        //assert
        $groupMember = factory(GroupMember::class)
            ->repository()
            ->find($memberRequestingToJoin);
        assertEquals(GroupMemberStatus::DEMANDE, $groupMember->object()
                                                             ->getGroupMemberStatus());
    }

    public function testRemoveAdminRoleFromMember()
    {
        //init
        $group = GroupFactory::random();

        //groupAdmin
        $groupAdminUser = UserFactory::random();
        $groupAdminMember = $this->createNewGroupMemberWith($groupAdminUser, $group, GroupMemberStatus::ACTIF,
                                                            [GroupMember::ROLE_GROUP_ADMIN, GroupMember::ROLE_GROUP_USER]);

        //test
        $groupAdminMember = $this->entityManager->getRepository(GroupMember::class)
                                                ->find($groupAdminMember->object());


        $this->service->removeAdminRoleFromMember($groupAdminMember);

        //assert
        $groupMember = factory(GroupMember::class)
            ->repository()
            ->find($groupAdminMember);

        self::assertNotContains(GroupMember::ROLE_GROUP_ADMIN, $groupMember->object()
                                                                           ->getMemberRoles());
    }

    public function testIsGroupAdmin()
    {
        //init
        $group = GroupFactory::random();

        //groupAdmin
        $groupAdminUser = UserFactory::random();
        $groupAdminMember = $this->createNewGroupMemberWith($groupAdminUser, $group, GroupMemberStatus::ACTIF,
                                                            [GroupMember::ROLE_GROUP_ADMIN, GroupMember::ROLE_GROUP_USER]);


        //member who has requested to join
        $memberUser = UserFactory::random();
        $nonAdmin = $this->createNewGroupMemberWith($memberUser, $group, GroupMemberStatus::ACTIF,
                                                    [GroupMember::ROLE_GROUP_USER]);

        //test and assert
        self::assertTrue($this->service->isGroupAdmin($groupAdminUser->object(), $group->object()));
        self::assertFalse($this->service->isGroupAdmin($memberUser->object(), $group->object()));


    }


    /**
     * @param Proxy|User  $user
     * @param Proxy|Group $group
     * @param string|null $statusString
     * @param array       $roles
     * @return Proxy
     */
    private function createNewGroupMemberWith(Proxy|User $user, Proxy|Group $group, ?string $statusString, array $roles): Proxy
    {
        $groupMemberFactory = factory(GroupMember::class);

        $status = $statusString != null ? $this->getStatusFromString($statusString) : null;

        return $groupMemberFactory->create(
            [
                'user' => $user,
                'groupOfMember' => $group,
                'groupMemberStatus' => $status,
                'memberRoles' => $roles
            ]);

    }

    /**
     * @param $statusString
     * @return Proxy
     */
    private function getStatusFromString($statusString): Proxy
    {
        $groupMemberStatusFactory = factory(GroupMemberStatus::class);
        return $groupMemberStatusFactory->find(['label' => $statusString]);

    }

}
