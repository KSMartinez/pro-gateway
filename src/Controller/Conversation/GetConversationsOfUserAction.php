<?php

namespace App\Controller\Conversation;

use App\Entity\Conversation;
use App\Entity\User;
use App\Service\ConversationService;
use App\Service\UserService;
use Doctrine\ORM\ORMException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\AsController;

/**
 *
 */
#[AsController]
class GetConversationsOfUserAction extends AbstractController
{

    /**
     * @param ConversationService $conversationService
     */
    public function __construct(private ConversationService $conversationService)
    {

    }

    /**
     * @param int $id
     * @return Conversation[]
     * @throws ORMException
     */
    public function __invoke(int $id): array
    {
        return $this->conversationService->getConversationsOfUserId($id);
    }
}