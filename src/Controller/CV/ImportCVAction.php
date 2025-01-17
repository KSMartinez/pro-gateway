<?php


namespace App\Controller\CV;


use App\Entity\CV;
use App\Service\CVService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpKernel\Attribute\AsController;

/**
 * Class ImportCVAction
 * @package App\Controller\User
 */
#[AsController]
class ImportCVAction extends AbstractController
{
     
    /**
     * @var CVService
     */
    private CVService $cvService;

    /**
     * importCvAction constructor.
     * @param CVService $cvService
     */
    public function __construct(CVService $cvService)
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