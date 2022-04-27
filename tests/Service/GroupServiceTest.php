<?php

namespace App\Tests\Service;

use App\Entity\Group;
use App\Entity\GroupStatus;
use App\Entity\User;
use App\Model\GroupDemand;
use App\Service\GroupService;
use DateTime;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use function Zenstruck\Foundry\faker;

/**
 *
 */
class GroupServiceTest extends KernelTestCase
{

    /**
     * @var ObjectManager
     */
    private ObjectManager $entityManager;
    /**
     * @var ContainerInterface
     */
    private ContainerInterface $c;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        self::bootKernel();
        $this->c = self::$kernel->getContainer();
        $this->entityManager = $this->c->get('doctrine')->getManager();
    }

    /**
     */
    public function testRejectGroupDemand()
    {
        $this->entityManager->getConnection()->beginTransaction();
        $groupStatusEnAttente = new GroupStatus();
        $groupStatusEnAttente->setStatus(GroupStatus::EN_ATTENTE);
        $this->entityManager->persist($groupStatusEnAttente);


        $groupStatusRefuse = new GroupStatus();
        $groupStatusRefuse->setStatus(GroupStatus::REFUSE);
        $this->entityManager->persist($groupStatusRefuse);

        $user = new User();
        $user->setFrequency(30)
            ->setEmail('test@test.com');
        $this->entityManager->persist($user);

        $group = new Group();
        $group->setGroupStatus($groupStatusEnAttente)
            ->setName('Test Group')
            ->setDescription('This is a test group')
            ->setCreatedBy($user)
            ->setDateCreated(new DateTime('now'));
        $this->entityManager->persist($group);

        $this->entityManager->flush();

        $groupDemand = new GroupDemand();
        $groupDemand->setGroup($group)
            ->setNotificationMessage("This group was rejected");

        $groupService = $this->c->get(GroupService::class);
        $groupService->rejectGroupDemand($groupDemand);

        $groupRepo = $this->entityManager->getRepository(Group::class);
        $group = $groupRepo->find($group->getId());

        self::assertNotNull($group);
        self::assertEquals(GroupStatus::REFUSE, $group->getGroupStatus()->getStatus());
        $this->entityManager->getConnection()->rollBack();
    }

    /**
     * @return void
     */
    public function testGetGroupDemands()
    {
        $this->entityManager->getConnection()->beginTransaction();
        $groupStatusEnAttente = new GroupStatus();
        $groupStatusEnAttente->setStatus(GroupStatus::EN_ATTENTE);
        $this->entityManager->persist($groupStatusEnAttente);


        $groupStatusConfirmed = new GroupStatus();
        $groupStatusConfirmed->setStatus(GroupStatus::CONFIRME);
        $this->entityManager->persist($groupStatusConfirmed);

        $user = new User();
        $user->setFrequency(30)
             ->setEmail('test@test.com');
        $this->entityManager->persist($user);

        $group = new Group();
        $group->setGroupStatus($groupStatusEnAttente)
              ->setName('Test Group')
              ->setDescription('This is a test group')
              ->setCreatedBy($user)
              ->setDateCreated(new DateTime('now'));
        $this->entityManager->persist($group);

        $this->entityManager->flush();

        $groupDemand = new GroupDemand();
        $groupDemand->setGroup($group)
                    ->setNotificationMessage("This group was confirmed");

        $groupService = $this->c->get(GroupService::class);
        $groupService->validateGroupDemand($groupDemand);

        $groupRepo = $this->entityManager->getRepository(Group::class);
        $group = $groupRepo->find($group->getId());

        self::assertNotNull($group);
        self::assertEquals(GroupStatus::CONFIRME, $group->getGroupStatus()->getStatus());
        $this->entityManager->getConnection()->rollBack();
    }

    /**
     * @return void
     */
    public function testValidateGroupDemand()
    {

    }
}
