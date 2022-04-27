<?php


namespace App\Controller\User;


use App\Entity\User; 
use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\AsController;

/**
 * Class CharteDutilisationAction
 * @package App\Controller\User
 */
#[AsController]
class UpdateProfilAction extends AbstractController
{
     
    /**
     * @var UserService
     */
    private UserService $userService;

    /**
     * CharteDutilisationAction constructor.
     * @param UserService $userService
     */
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }
 

    /**
     * @param User $data
     * @return User   
     */   
    public function __invoke(User $data): User 
    {
  
      return  $this->userService->update($data);      


    }       
}   