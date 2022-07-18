<?php

namespace App\Service;

use App\Entity\Conversation;
use App\Entity\User;
use App\Repository\ConversationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\ORMException;
use Exception;

/**
 *
 */
class ConversationService
{

    /**
     * @param ConversationRepository $conversationRepository
     */
    public function __construct(private EntityManagerInterface $entityManager, private ConversationRepository $conversationRepository)
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

    /**
     * @param int $userId
     * @return Conversation[]
     * @throws ORMException
     * @throws Exception
     */
    public function getConversationsOfUserId(int $userId): array
    {
        $user = $this->entityManager->getReference(User::class, $userId);
        if (!$user){
            throw new Exception('User does not exist');
        }
        return $this->getConversationsOfUser($user);
    }

    /**
     * @param User $user1
     * @param User $user2
     * @return Conversation|null
     * @throws NonUniqueResultException
     */
    public function getConversationBetweenTwoUsers(User $user1, User $user2): ?Conversation
    {
        return $this->conversationRepository->getConversationBetweenUsers($user1, $user2);
    }

    /**
     * @param Conversation $conversation
     * @return Conversation
     * @throws Exception
     */
    public function createConversation(Conversation $conversation): Conversation
    {
        $users = $conversation->getUsers()->toArray();
        if (sizeof($users) != 2){
            throw new Exception('Number of Users in this conversation are not 2. Conversation can only be made between two users.');
        }
        $conversationResult = $this->getConversationBetweenTwoUsers($users[0], $users[1]);

        if ($conversationResult === null){
            $conversationResult = $conversation;
            $this->entityManager->persist($conversationResult);
            $this->entityManager->flush();
        }

        return $conversationResult;
    }
}