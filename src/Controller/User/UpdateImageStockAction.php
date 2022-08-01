<?php

namespace App\Controller\User;

use App\Entity\ImageStockCompatibleInterface;
use App\Service\ImageStockService;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\AsController;

#[AsController]
class UpdateImageStockAction  extends AbstractController
{
    /**
     * @param ImageStockService $imageStockService
     */
    public function __construct(private ImageStockService $imageStockService)
    {
    }

    /**
     * @return ImageStockCompatibleInterface|void
     *
     * @throws Exception
     */
    public function __invoke()
    {
        return $this->imageStockService->updatePicture('App\Entity\User');
    }
}