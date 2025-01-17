<?php

namespace App\Controller\Offer;

use Exception;
use App\Entity\Offer;
use App\Service\OfferService;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Class UpdateLogoAction
 * @package App\Controller\Offer
 */
#[AsController]
class UpdateLogoAction extends AbstractController
{

    /**
     * UpdateLogoAction constructor.
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
        return $this->offerService->updateLogo();
    }
}     