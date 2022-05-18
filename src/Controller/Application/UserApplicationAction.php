<?php


namespace App\Controller\Application;


use Exception;
use App\Entity\Application;
use App\Service\ApplicationService;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Class UserApplicationAction
 * @package App\Controller\Application  
 */
#[AsController]
class UserApplicationAction extends AbstractController
{

    /**
     * @var ApplicationService
     */ 
    private ApplicationService $applicationService;

    /**
     * UserApplicationAction constructor.  
     * @param ApplicationService $applicationService
     */
    public function __construct(ApplicationService $applicationService)
    {
        $this->applicationService = $applicationService;
    }   
        
   
    /**
     * @param Application $data  
     * @return Application   
     * @throws Exception
     */
    public function __invoke(Application $data): Application
    {
        return $this->applicationService->userApplication($data);
    }
}             