<?php

namespace App\Tests\Service;

use App\Entity\Conversation;
use App\Entity\User;
use App\Factory\UserFactory;
use App\Service\ConversationService;
use Doctrine\Persistence\ObjectManager;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use function PHPUnit\Framework\assertEquals;
use function Zenstruck\Foundry\factory;

class ConversationServiceTest extends KernelTestCase
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

    public function testGetConversationsOfUser()
    {
        $user1 = UserFactory::random();
        $user2 = UserFactory::random();
        $user3 = UserFactory::random();

        $conversation1 = factory(Conversation::class)->create(['users' => [$user1, $user2] ]);
        $conversation2 = factory(Conversation::class)->create(['users' => [$user1, $user3] ]);

        //$user1 = $this->entityManager->getRepository(User::class)->find($user1->object()->getId());

        /** @var ConversationService $service */
        $service = $this->c->get(ConversationService::class);

        $conversations = $service->getConversationsOfUser($user1->object());

        assertEquals(2, sizeof($conversations));

        $user4 = UserFactory::random();

        factory(Conversation::class)->create(['users' => [$user1, $user4]]);
        $conversations = $service->getConversationsOfUser($user1->object());

        assertEquals(3, sizeof($conversations));

    }

    public function testGetConversationBetweenTwoUsers(){

        $user1 = UserFactory::random();
        $user2 = UserFactory::random();
        $user3 = UserFactory::random();

        $conversation1 = factory(Conversation::class)->create(['users' => [$user1, $user2] ]);
        $conversation2 = factory(Conversation::class)->create(['users' => [$user1, $user3] ]);
        factory(Conversation::class)->create(['users' => [$user1, $user3, $user2] ]);
        //$user1 = $this->entityManager->getRepository(User::class)->find($user1->object()->getId());

        /** @var ConversationService $service */
        $service = $this->c->get(ConversationService::class);

        /** @var Conversation $conversation */
        $conversation = $service->getConversationBetweenTwoUsers($user1->object(), $user2->object());

        assertEquals($conversation1->object()->getId(), $conversation->getId());

       /* $user4 = UserFactory::random();

        factory(Conversation::class)->create(['users' => [$user1, $user4]]);
        $conversations = $service->getConversationsOfUser($user1->object());

        assertEquals(3, sizeof($conversations));*/

    }
}
