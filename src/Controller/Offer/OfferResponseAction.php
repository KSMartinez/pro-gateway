<?php


namespace App\Controller\Offer;


use App\Entity\Offer;
use App\Service\OfferService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\AsController;

    
/**
 * Class OfferResponseAction  
 * @package App\Controller\Offer    
 */
#[AsController]    
class OfferResponseAction extends AbstractController
{      

    /**   
     * @var OfferService
     */
    private OfferService $offerService;

    /**
     * OfferResponseAction constructor.
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
        $this->offerService->offerResponse($data);
        return $data;
    }
}