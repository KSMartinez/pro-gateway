<?php


namespace App\Controller\Experience; 


use App\Entity\Experience;
use App\Service\ExperienceService;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Class CheckExperienceDatasAction  
 * @package App\Controller\Offer
 */  
#[AsController]
class CheckExperienceDatasAction extends AbstractController
{

    /**
     * @var ExperienceService
     */  
    private ExperienceService $educationService;

    /**
     * CheckExperienceDatasAction constructor.
     * @param ExperienceService $experienceService
     */
    public function __construct(ExperienceService $experienceService)
    {
        $this->experienceService = $experienceService;
    }

    /**
     * @param Experience $data 
     * @return bool 
     */
    public function __invoke(Experience $data): bool 
    {

       return  $this->experienceService->checkDatas($data);
        
    }
}   