<?php


namespace App\Service;

use App\Entity\Offer;
use App\Entity\OfferStatus;
use App\Entity\User;
use App\Repository\OfferRepository;
use App\Repository\OfferStatusRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\Security\Core\Security;

/**
 *
 */
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
     * @param NotificationService    $notificationService
     * @param OfferStatusRepository  $offerStatusRepository
     * @param EntityManagerInterface $entityManager
     * @param OfferRepository        $offerRepository
     */
    public function __construct(private NotificationService $notificationService, private OfferStatusRepository $offerStatusRepository, private Security $security, EntityManagerInterface $entityManager, OfferRepository $offerRepository)
    {
        $this->offerRepository = $offerRepository;
        $this->entityManager = $entityManager;
    }

    /**
     * @param Offer $offer
     * @return Offer
     * @throws Exception
     */
    public function validateOffer(Offer $offer): Offer
    {

        if (!$offer->getId()) {
            throw new Exception('The offer should have an id for updating');
        }
        if (!$this->offerRepository->find($offer->getId())) {
            throw new Exception('The offer should have an id for updating');
        }

        $offerStatusPubliee = $this->getOfferStatus(OfferStatus::PUBLIEE);

        $offer->setOfferStatus($offerStatusPubliee);

        $this->entityManager->persist($offer);
        $this->entityManager->flush();

        return $offer;

    }

    /**
     * @param Offer $offer
     * @return Offer
     * @throws Exception
     */
    public function createNewOffer(Offer $offer): Offer
    {


        /** @var User $user */
        $user = $this->security->getUser();

        //check if user is admin
        if (in_array(User::ROLE_ADMIN, $user->getRoles())){

            $offerStatusToSet = $this->getOfferStatus(OfferStatus::PUBLIEE);
        }else{
            $offerStatusToSet = $this->getOfferStatus(OfferStatus::ATTENTE_DE_VALIDATION);
        }
        $offer->setOfferStatus($offerStatusToSet);

        $this->entityManager->persist($offer);

        $this->notificationService->createOfferNotification($offer);

        $this->entityManager->flush();

        return $offer;

    }


    /**
     * @param offer $offer
     * @return offer  
     * @throws Exception
     */
    public function updateLogo(Offer $offer): Offer
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

    /**
     * @param string $status
     * @return OfferStatus
     * @throws Exception
     */
    private function getOfferStatus(string $status): OfferStatus
    {
        $offerStatus = $this->offerStatusRepository->findOneBy(array('label' => $status));
        if (!$offerStatus) {
            throw new Exception('OfferStatus with label ' . $status . ' was not found in the table. Please add it correctly.');
        }
        return $offerStatus;
    }

    /**
     * @param Offer $offer
     * @return Offer
     * @throws Exception
     */
    public function refuseOffer(Offer $offer): Offer
    {
        $offerStatusRefuse = $this->getOfferStatus(OfferStatus::REFUSE);



        $offer->setOfferStatus($offerStatusRefuse);


        $this->entityManager->persist($offer);
        $this->entityManager->flush();

        return $offer;

    }

    /**
     * @param Offer $offer
     * @return Offer
     * @throws Exception
     */
    public function archiveOffer(Offer $offer): Offer
    {
        $offerStatusArchivee = $this->getOfferStatus(OfferStatus::ARCHIVEE);


        $offer->setOfferStatus($offerStatusArchivee);


        $this->entityManager->persist($offer);
        $this->entityManager->flush();

        return $offer;
    }

    /**
     * @param Offer $offer
     * @return Offer
     * @throws Exception
     */
    public function deleteOffer(Offer $offer): Offer
    {
        $offerStatusSupprime = $this->getOfferStatus(OfferStatus::SUPPRIMEE);


        $offer->setOfferStatus($offerStatusSupprime);


        $this->entityManager->persist($offer);
        $this->entityManager->flush();

        return $offer;
    }

    /**
     * @param Offer $offer
     * @return Offer
     * @throws Exception
     */
    public function setFulfilled(Offer $offer): Offer
    {
        $offerStatusPourvue = $this->getOfferStatus(OfferStatus::POURVUE);


        $offer->setOfferStatus($offerStatusPourvue);


        $this->entityManager->persist($offer);
        $this->entityManager->flush();

        return $offer;
    }

    /**
     * @param Offer $offer
     * @return Offer
     * @throws Exception
     */
    public function reactivateOffer(Offer $offer): Offer
    {
        //change the date published to today
        $offer->setDatePosted(new DateTime('now'));

        //set it back to attent de validation
        return $this->setValidationStatus($offer);
    }

    /**
     * @param Offer $offer
     * @return Offer
     * @throws Exception
     */
    public function setValidationStatus(Offer $offer): Offer
    {

        /** @var User $user */
        $user = $this->security->getUser();
        if (in_array(User::ROLE_ADMIN, $user->getRoles())){
            $offerStatusToSet = $this->getOfferStatus(OfferStatus::PUBLIEE);
        }else{
            $offerStatusToSet = $this->getOfferStatus(OfferStatus::ATTENTE_DE_VALIDATION);
        }


        $offer->setOfferStatus($offerStatusToSet);

        $this->entityManager->persist($offer);
        $this->entityManager->flush();

        return $offer;
    }

    /**
     * @param array<string> $ids
     * @throws Exception
     * @return void
     */
    public function deleteMultipleOffers(array $ids)
    {

        foreach ($ids as $id) {
            $offer = $this->offerRepository->find($id);
            if ($offer) {
                $this->deleteOffer($offer);
            }
        }
    }

    /**
     * @param array<string> $ids
     * @return void
     * @throws Exception
     */
    public function validateMultipleOffers(array $ids)
    {
        foreach ($ids as $id) {

            $offer = $this->offerRepository->find($id);
            if ($offer){
                $this->validateOffer($offer);
            }
        }
    }


}   