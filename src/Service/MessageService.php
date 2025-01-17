<?php

namespace App\Service;

use App\Entity\Message;
use App\Entity\MessageStatus;
use App\Repository\MessageStatusRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;

class MessageService
{

    public function __construct(private EntityManagerInterface $entityManager, private MessageStatusRepository $messageStatusRepository)
    {
    }

    /**
     * @param Message $message
     * @param string  $status
     * @return Message
     * @throws Exception
     */
    private function saveMessage(Message $message, string $status): Message
    {

        $messageStatus = $this->messageStatusRepository->findOneBy(['label' => $status]);

        if (!$messageStatus) {
            throw new Exception('Status ' . $status . ' not found in the MessageStatus table. Please add it');
        }

        $message->setMessageStatus($messageStatus);

        $this->entityManager->persist($message);
        $this->entityManager->flush();

        return $message;
    }


    /**
     * @param Message $message
     * @return Message
     * @throws Exception
     */
    public function updateMessage(Message $message): Message
    {
        return $this->saveMessage($message, MessageStatus::OPEN);
    }


    /**
     * @param Message $message
     * @return Message
     * @throws Exception
     */
    public function newMessage(Message $message): Message
    {
        return $this->saveMessage($message, MessageStatus::NEW);
    }

    /**
     * @param Message $data
     * @return Message
     */
    public function deleteMessage(Message $data): Message
    {
        $this->entityManager->remove($data);
        $this->entityManager->flush();
        return $data;
    }

}