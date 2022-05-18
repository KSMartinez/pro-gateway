<?php


namespace App\Controller\Offer;


use App\Entity\Offer;
use App\Service\OfferService;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\AsController;

/**
 * Class ValidateOfferAction
 * @package App\Controller\Offer
 */
#[AsController]
class ValidateOfferAction extends AbstractController
{

    /**
     * @var OfferService
     */
    private OfferService $offerService;

    /**
     * ValidateOfferAction constructor.
     * @param OfferService $offerService
     */
    public function __construct(OfferService $offerService)
    {
        $this->offerService = $offerService;
    }

    /**
     * @param Offer $data
     * @return Offer
     * @throws Exception
     */
    public function __invoke(Offer $data): Offer
    {
        return $this->offerService->validateOffer($data);
    }
}