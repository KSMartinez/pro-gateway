<?php


namespace App\Controller\News;


use Exception;
use App\Entity\News; 
use App\Service\NewsService;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Class UpdatePictureAction
 * @package App\Controller\News
 */
#[AsController]
class UpdatePictureAction extends AbstractController
{

    /**
     * @var NewsService
     */
    private NewsService $newsService;

    /**
     * UpdatePictureAction constructor.
     * @param NewsService $newsService
     */
    public function __construct(NewsService $newsService)
    {
        $this->newsService = $newsService;
    }
 

    /**
     * @param News $data  
     * @return News
     * @throws Exception
     */
    public function __invoke(News $data): News
    {
        return $this->newsService->updatePicture($data);
    }
}     