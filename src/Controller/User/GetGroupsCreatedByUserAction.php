<?php

namespace App\Controller\User;

use App\Entity\User;
use App\Service\GroupService;
use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 *
 */
class GetGroupsCreatedByUserAction extends AbstractController
{

    /**
     * @param UserService $userService
     */
    public function __construct(private UserService $userService)
    {
    }

    /**
     * @param User $data
     * @return void
     */
    public function __invoke(User $data)
    {
        $this->userService->getGroupsCreatedByUser($data);
    }
}