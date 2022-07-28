<?php

namespace App\Controller\Offer;

use App\Entity\Offer;
use App\Service\OfferService;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\AsController;

#[AsController]
class UpdateImageStockAction extends AbstractController
{

    public function __construct(private OfferService $offerService)
    {
    }

    /**
     * @return Offer|void
     * 
     * @throws Exception
     */
    public function __invoke()
    {
        return $this->offerService->updateImageStock();
    }
}
