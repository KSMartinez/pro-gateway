<?php


namespace App\Controller\News;


use App\Entity\News;
use App\Service\NewsService;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\AsController;

/**
 * Class UpdatePictureAction
 * @package App\Controller\News
 */
#[AsController]
class UpdatePictureAction extends AbstractController
{

    /**
     * @param NewsService $newsService
     */
    public function __construct(private NewsService $newsService)
    {
    }

    /**
     * @return News
     * @throws Exception
     */
    public function __invoke(): News
    {
        return $this->newsService->updatePicture();
    }
}     