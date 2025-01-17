<?php


namespace App\Controller\User;


use Exception;
use App\Entity\User; 
use App\Service\UserService;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Class RejectedCharteAction
 * @package App\Controller\User
 */
#[AsController]
class RejectedCharteAction extends AbstractController
{

    /**
     * @var UserService
     */
    private UserService $userService;

    /**
     * RejectedCharteAction constructor.
     * @param UserService $userService
     */
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }
 

    /**
     * @param User $data  
     * @return User
     * @throws Exception
     */
    public function __invoke(User $data): User
    {
        return $this->userService->rejectedCharte($data);
    }
}         