<?php

namespace App\Controller\User;

use App\Entity\Conversation;
use App\Entity\User;
use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\AsController;

/**
 *
 */
#[AsController]
class GetConversationsOfUserAction extends AbstractController
{

    /**
     * @param UserService $userService
     */
    public function __construct(private UserService $userService)
    {

    }

    /**
     * @param User $data
     * @return Conversation[]
     */
    public function __invoke(User $data): array
    {
        return $this->userService->getConversationsOfUser($data);
    }
}