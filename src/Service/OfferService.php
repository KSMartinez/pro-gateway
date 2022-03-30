<?php


namespace App\Service;


use App\Entity\Offer;
use Doctrine\ORM\EntityManagerInterface;

class OfferService
{

    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;

    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param Offer $offer
     * @return void
     */
    public function validateOffer(Offer $offer)
    {

        $offer->setIsValid(true);
        $this->entityManager->persist($offer);
        $this->entityManager->flush();

    }
}