<?php


namespace App\Controller\Offer;


use App\Entity\Offer;
use App\Model\OfferDemand;
use App\Service\OfferService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\AsController;

    
/**
 * Class RefuseOfferAction
 * @package App\Controller\Offer    
 */
#[AsController]    
class RefuseOfferAction extends AbstractController
{      

    /**   
     * @var OfferService
     */
    private OfferService $offerService;

    /**
     * @param OfferService $offerService
     */
    public function __construct(OfferService $offerService)
    {
        $this->offerService = $offerService;
    }

    /**
     * @param OfferDemand $data
     * @return Offer
     */
    public function __invoke(OfferDemand $data): Offer
    {
        return $this->offerService->refuseOffer($data);

    }
}