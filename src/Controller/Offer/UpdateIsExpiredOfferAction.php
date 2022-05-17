<?php

   
namespace App\Controller\Offer;


use App\Entity\Offer;
use App\Service\OfferService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\AsController;


/**
 * Class UpdateIsExpiredOfferAction     
 * @package App\Controller\Offer
 */
#[AsController]
class UpdateIsExpiredOfferAction extends AbstractController
{

    /**   
     * @var OfferService
     */
    private OfferService $offerService;

    /**
     * UpdateIsExpiredOfferAction constructor.
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
        $this->offerService->UpdateIsExpiredOffer($data);
        return $data;
    }
          

     
}