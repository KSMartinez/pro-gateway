<?php

namespace App\Controller\News;

use App\Entity\News;
use App\Service\NewsService;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\AsController;

#[AsController]
class UpdateImageStockAction extends AbstractController
{

    public function __construct(private NewsService $newsService)
    {
    }

    /**
     * @return News|void
     * 
     * @throws Exception
     */
    public function __invoke()
    {
        return $this->newsService->updateImageStock();
    }
}
