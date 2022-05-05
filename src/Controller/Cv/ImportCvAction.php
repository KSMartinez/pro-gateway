<?php


namespace App\Controller\Cv; 


use App\Entity\CV;
use App\Entity\User; 
use App\Service\CvService;  
use App\Service\UserService;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Class CharteDutilisationAction
 * @package App\Controller\User
 */
#[AsController]
class ImportCvAction extends AbstractController
{
     
    /**
     * @var CvService
     */
    private CvService $cvService;

    /**
     * importCvAction constructor.
     * @param CvService $cvService
     */
    public function __construct(CvService $cvService)
    {
        $this->cvService = $cvService;
    }
 

    /**
     * @param File $file 
     * @return CV   
     */   
    public function __invoke(File $file): CV   
    {   

      return  $this->cvService->upload($file);        


    }       
}   