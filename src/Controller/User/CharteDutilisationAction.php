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
class CharteDutilisationAction extends AbstractController
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
 

    # For a test with Apiplatform, we can set $charteSigned to true or false and see the result 
    # in the database 

    # $user & $charteSigned will be given by the front 

    /**
     * @param User $data
     * @return User   
     */   
    public function __invoke(User $data, $charteSigned): User 
    {
          
      # Whatever for the connection or for the Profil Parameters page, we call the same function 
      
      return  $this->userService->charteUtilisation($data, $charteSigned);      


    }       
}   