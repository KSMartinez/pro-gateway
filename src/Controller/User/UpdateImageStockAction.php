<?php

namespace App\Controller\User;

use App\Entity\User;
use App\Service\UserService;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\AsController;

#[AsController]
class UpdateImageStockAction  extends AbstractController
{
    public function __construct(private UserService $userService)
    {
    }

    /**
     * @return User|void
     *
     * @throws Exception
     */
    public function __invoke()
    {
        return $this->userService->updateImageStock();
    }
}