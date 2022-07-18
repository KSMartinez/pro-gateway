<?php

namespace App\Controller\Conversation;

use App\Entity\Conversation;
use App\Service\ConversationService;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\AsController;

#[AsController]
class CreateConversationAction extends AbstractController
{
    public function __construct(private ConversationService $conversationService)
    {

    }

    /**
     * @param Conversation $data
     * @return Conversation
     * @throws Exception
     */
    public function __invoke(Conversation $data): Conversation
    {
        return $this->conversationService->createConversation($data);
    }
}