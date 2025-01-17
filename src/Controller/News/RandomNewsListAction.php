<?php


namespace App\Controller\News; 

    
use Exception;
use App\Entity\News;
use App\Service\NewsService;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Class RandomNewsListAction     
 * @package App\Controller\News   
 */  
#[AsController]
class RandomNewsListAction extends AbstractController
{

    /**
     * @var NewsService
     */  
    private NewsService $newsService;

    /**
     * CheckDatasAction constructor.  
     * @param NewsService $newsService
     */
    public function __construct(NewsService $newsService)
    {
        $this->newsService = $newsService;
    }

  

    /**
     * @return News[]       
     */
    public function __invoke()   
    {

       return  $this->newsService->randomNewsList();
        
    }
}      