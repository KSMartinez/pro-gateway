<?php


namespace App\Controller\User;


use App\Entity\User; 
use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\AsController;

/**
 * Class UserListAction
 * @package App\Controller\User
 */
#[AsController]
class UserListAction extends AbstractController
{

    /**
     * @var UserService
     */
    private UserService $userService;

    /**
     * AlphabeticListUserAction constructor.
     * @param UserService $userService
     */
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }
 


    /**
     * @return Array<User>     
     */   
    public function __invoke() 
    {
            
      return  $this->userService->userList();      


    }       
}   