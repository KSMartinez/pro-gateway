<?php


namespace App\Controller\Education; 


use App\Entity\Education;
use App\Service\EducationService;
use Exception;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Class CheckDatasAction  
 * @package App\Controller\Offer
 */  
#[AsController]
class CheckDatasAction extends AbstractController
{

    /**
     * @var EducationService
     */  
    private EducationService $educationService;

    /**
     * CheckDatasAction constructor.
     * @param EducationService $educationService
     */
    public function __construct(EducationService $educationService)
    {
        $this->educationService = $educationService;
    }

    /**
     * @param Education $data
     * @return bool
     * @throws Exception
     */
    public function __invoke(Education $data): bool 
    {

       return  $this->educationService->checkDatas($data);
        
    }
}   