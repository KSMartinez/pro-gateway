<?php

namespace App\Service;

use App\Entity\Conversation;
use App\Entity\User;
use App\Repository\ConversationRepository;

/**
 *
 */
class ConversationService
{

    /**
     * @param ConversationRepository $conversationRepository
     */
    public function __construct(private ConversationRepository $conversationRepository)
    {
    }

    /**
     * @param User $user
     * @return Conversation[]
     */
    public function getConversationsOfUser(User $user): array
    {
        return $this->conversationRepository->getConversationsOfUser($user);
    }
}