<?php


namespace App\Service;

use App\Entity\Notification;
use DateTime;
use Exception;
use App\Entity\Offer;
use DateTimeImmutable;
use App\Repository\OfferRepository;
use Doctrine\ORM\EntityManagerInterface;

class OfferService
{

    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;

     /**
     * @var OfferRepository      
     */
    private OfferRepository $offerRepository;
  
    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager, OfferRepository $offerRepository)
    {
        $this->offerRepository= $offerRepository; 
        $this->entityManager = $entityManager;
    }
   
    /**
     * @param Offer $offer
     * @return void
     */
    public function validateOffer(Offer $offer)
    {

        
        if (!$offer->getId()) {   
            throw new Exception('The offer should have an id for updating');
        }
        if (!$this->offerRepository->find($offer->getId())) {
            throw new Exception('The offer should have an id for updating');
   
        }   


        $offer->setIsValid(true);
        $this->entityManager->persist($offer);
        $this->entityManager->flush();

    }


     /**
     * @param Offer $offer
     * @return void 
     */
    public function updateAndSetPublishedAt(Offer $offer)
    {

                
        if (!$offer->getId()) {   
            throw new Exception('The offer should have an id for updating');
        }
        if (!$this->offerRepository->find($offer->getId())) {
            throw new Exception('The offer should have an id for updating');
   
        }   
      
        $offer->setPublishedAt(new DateTimeImmutable('now'));
        $this->entityManager->persist($offer);
        $this->entityManager->flush();

    }  
   
    /**  
     * @param Offer $data  
     * @return void   
     */
    public function UpdateIsExpiredOffer(Offer $offer){
  

        if (!$offer->getId()) {   
            throw new Exception('The offer should have an id for updating');
        }
        if (!$this->offerRepository->find($offer->getId())) {
            throw new Exception('The offer should have an id for updating');
   
        }   
   
        $value = !$offer->getIsExpired(); 

        # The offer is reactivated  
        if( !$value ){
            $offer->setInReactivation(true); 
            $offer->setDateReactivated(new DateTimeImmutable('now')); 
        }
        $offer->setIsExpired($value);
        $this->entityManager->persist($offer);      
        $this->entityManager->flush();
    }      


    
    /**  
     * @param Offer $data  
     * @return void   
     */
    public function createOfferWithNotification(Offer $offer){



        $this->entityManager->persist($offer);      
        $this->entityManager->flush();

        # Send the notification to the administrator, it's a email aspect, we'll end it with Akhil 

        $notification = new Notification(); 
        $notification->setOffer($offer); 
        # ...  
      
       
    }

    /**  
     * @param Offer $offer
     * @return void   
     */
    public function offerResponse(Offer $offer)
    {

        if (!$offer->getId()) {   
            throw new Exception('The offer should have an id for updating');
        }
        if (!$this->offerRepository->find($offer->getId())) {
            throw new Exception('The offer should have an id for updating');
   
        }   

        $response = $offer->getOfferResponse(); 

        # Send the $response to $offer.getUser(), mail aspect to master woth Akhil  
    }


     /**   
     * @param offer $offer
     * @return offer  
     * @throws Exception
     */
    public function updateLogo(Offer $offer)
    {

        if (!$offer->getId()) {
            throw new Exception('The offer should have an id for updating');
        }
        if (!$this->offerRepository->find($offer->getId())) {
            throw new Exception('The offer should have an id for updating');
       
        }  
        
        $this->entityManager->persist($offer);
        $this->entityManager->flush();
        return $offer;       


    }


           
    
}   