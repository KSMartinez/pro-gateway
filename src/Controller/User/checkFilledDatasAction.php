<?php


namespace App\Controller\User;


use Exception;
use App\Entity\User; 
use App\Service\UserService;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Class checkFilledDatasAction
 * @package App\Controller\User
 */
#[AsController]
class checkFilledDatasAction extends AbstractController
{

    /**
     * @var UserService
     */
    private UserService $userService;

    /**
     * checkFilledDatasAction constructor.
     * @param UserService $userService
     */
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }
 

    /**
     * @param User $data  
     * @return bool
     * @throws Exception
     */
    public function __invoke(User $data): bool
    {
        return $this->userService->checkFilledDatas($data);
    }
}         