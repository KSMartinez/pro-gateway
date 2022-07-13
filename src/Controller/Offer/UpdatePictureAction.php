<?php

namespace App\Controller\Offer;

use App\Entity\Offer;
use App\Service\OfferService;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UpdatePictureAction extends AbstractController
{
    /**
     * @param OfferService $offerService
     */
    public function __construct(private OfferService $offerService)
    {
    }

    /**
     * @return Offer
     * @throws Exception
     */
    public function __invoke(): Offer
    {
        return $this->offerService->updatePicture();
    }
}