<?php


namespace App\Controller\Offer;


use App\Entity\Offer;
use App\Service\OfferService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\AsController;


/**
 * Class UpdateAndSetPublishedAtAction
 * @package App\Controller\Offer
 */
#[AsController]
class UpdateOfferAction extends AbstractController
{

    /**
     * @var OfferService
     */
    private OfferService $offerService;

    /**
     * UpdateAndSetPublishedAtAction constructor.
     * @param OfferService $offerService
     */
    public function __construct(OfferService $offerService)
    {
        $this->offerService = $offerService;
    }
    
    /**
     * @param Offer $data
     * @return Offer   
     */
    public function __invoke(Offer $data): Offer
    {
        $this->offerService->updateAndSetPublishedAt($data);
        return $data;
    }
}