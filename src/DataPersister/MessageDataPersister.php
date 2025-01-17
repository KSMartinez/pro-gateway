<?php

namespace App\DataPersister;

use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use App\Entity\Message;
use App\Service\MessageService;
use Exception;

class MessageDataPersister implements ContextAwareDataPersisterInterface
{

    public function __construct(private MessageService $messageService)
    {
    }

    /**
     * @param mixed        $data
     * @param array<mixed> $context
     * @return bool
     */
    public function supports(mixed $data, array $context = []): bool
    {
        return $data instanceof Message;
    }

    /**
     * @param Message      $data
     * @param array<mixed> $context
     * @return Message
     * @throws Exception
     */
    public function persist($data, array $context = []): Message
    {
        if ($data->getId() === null) {
            return $this->messageService->newMessage($data);
        }

        return $this->messageService->updateMessage($data);
    }

    /**
     * @param Message      $data
     * @param array<mixed> $context
     * @return Message
     */
    public function remove($data, array $context = []): Message
    {
        return $this->messageService->deleteMessage($data);
    }
}