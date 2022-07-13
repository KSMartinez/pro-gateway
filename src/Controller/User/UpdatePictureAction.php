<?php


namespace App\Controller\User;


use App\Entity\User;
use App\Service\UserService;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\AsController;

/**
 * Class UserListAction
 * @package App\Controller\User
 */
#[AsController]
class UpdatePictureAction extends AbstractController
{
    /**
     * @param UserService $userService
     */
    public function __construct(private UserService $userService)
    {
    }

    /**
     * @return User
     * @throws Exception
     */
    public function __invoke(): User
    {
        return $this->userService->updatePicture();
    }
}     