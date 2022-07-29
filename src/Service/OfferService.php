<?php

namespace App\Service;

use App\Entity\Offer;
use App\Entity\OfferStatus;
use App\Entity\User;
use App\Repository\OfferRepository;
use App\Repository\OfferStatusRepository;
use DateTime;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use RuntimeException;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Security;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class OfferService
{
    const CONTENT_TYPE_JSON = 'json';
    const DATA_JSON_PARAM = 'id';

    /**
     * @param NotificationService $notificationService
     * @param OfferStatusRepository $offerStatusRepository
     * @param Security $security
     * @param EntityManagerInterface $entityManager
     * @param OfferRepository $offerRepository
     * @param RequestStack $requestStack
     * @param NexusAPIService $nexusAPIService
     * @param ImageStockService $imageStockService
     */
    public function __construct(
        private NotificationService    $notificationService,
        private OfferStatusRepository  $offerStatusRepository,
        private Security               $security,
        private EntityManagerInterface $entityManager,
        private OfferRepository        $offerRepository,
        private RequestStack           $requestStack,
        private NexusAPIService        $nexusAPIService,
        private ImageStockService      $imageStockService
    )
    {
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
        if (in_array(User::ROLE_ADMIN, $user->getRoles())) {

            $offerStatusToSet = $this->getOfferStatus(OfferStatus::PUBLIEE);
        } else {
            $offerStatusToSet = $this->getOfferStatus(OfferStatus::ATTENTE_DE_VALIDATION);
        }
        $offer->setOfferStatus($offerStatusToSet);

        $this->entityManager->persist($offer);

        $this->notificationService->createOfferNotification($offer);

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
     * @return offer
     * @throws Exception
     */
    public function updateLogo(): Offer
    {
        if ($this->requestStack->getCurrentRequest() === null) {
            throw new Exception('Request is null');
        }
        $request = $this->requestStack->getCurrentRequest();
        $object = $request->attributes->get('data');
        if (!($object instanceof Offer)) {
            throw new RuntimeException('The object does not match');
        }
        if (!$object->getId()) {
            throw new Exception('The user should have an id for updating');
        }
        if (!$this->offerRepository->find($object->getId())) {
            throw new Exception('The user should have an id for updating');
        }

        $file = $request->files->get('logoFile');
        if ($file instanceof File) {
            $object->setLogoFile($file);
            $object->setUpdatedAt(new DateTimeImmutable());
            $this->entityManager->persist($object);
            $this->entityManager->flush();
        }

        return $object;
    }

    /**
     * @return Offer
     * @throws Exception
     */
    public function updatePicture(): Offer
    {
        if ($this->requestStack->getCurrentRequest() === null) {
            throw new Exception('Request is null');
        }
        $request = $this->requestStack->getCurrentRequest();
        $object = $request->attributes->get('data');
        if (!($object instanceof Offer)) {
            throw new RuntimeException('The object does not match');
        }
        if (!$object->getId()) {
            throw new Exception('The user should have an id for updating');
        }
        if (!$this->offerRepository->find($object->getId())) {
            throw new Exception('The user should have an id for updating');
        }
        $file = $request->files->get('imageFile');
        if ($file instanceof File) {
            $object->setFile($file);
            $object->setUpdatedAt(new DateTime());
            $this->entityManager->persist($object);
            $this->entityManager->flush();
        }

        return $object;
    }

    /**
     * @return Offer|void
     * @throws Exception
     */
    public function updateImageStock()
    {
        if ($this->requestStack->getCurrentRequest() === null) {
            throw new Exception('Request is null');
        }
        $request = $this->requestStack->getCurrentRequest();
        $object = $request->attributes->get('data');

        if (!($object instanceof Offer)) {
            throw new RuntimeException('The object does not match');
        }
        if (!$object->getId()) {
            throw new Exception('The object should have an id for updating');
        }
        if (!$this->offerRepository->find($object->getId())) {
            throw new Exception('The object should have an id for updating');
        }

        if ($request->getContentType() === self::CONTENT_TYPE_JSON) {
            $arrayDataJson = json_decode($request->getContent(), true);
            if (is_array($arrayDataJson)) {
                $imageStockIdReceived = $arrayDataJson[self::DATA_JSON_PARAM];
                $pathFilename = $this->imageStockService->imageStockIdExist($imageStockIdReceived);
                $object->setImagePath($pathFilename);
                $this->entityManager->flush();

                return $object;
            }
            throw new Exception();
        }
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
        if (in_array(User::ROLE_ADMIN, $user->getRoles())) {
            $offerStatusToSet = $this->getOfferStatus(OfferStatus::PUBLIEE);
        } else {
            $offerStatusToSet = $this->getOfferStatus(OfferStatus::ATTENTE_DE_VALIDATION);
        }

        $offer->setOfferStatus($offerStatusToSet);

        $this->entityManager->persist($offer);
        $this->entityManager->flush();

        return $offer;
    }

    /**
     * @param array<string> $ids
     * @return void
     * @throws Exception
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
     * @param array<string> $ids
     * @return void
     * @throws Exception
     */
    public function validateMultipleOffers(array $ids)
    {
        foreach ($ids as $id) {
            $offer = $this->offerRepository->find($id);
            if ($offer) {
                $this->validateOffer($offer);
            }
        }
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
     * @return Offer[]
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function pollNexusForNewOffers(): array
    {

        return $this->nexusAPIService->requestNexusForOffers();
    }

    /**
     * @param Offer[] $offers
     * @return void
     * @throws Exception
     */
    public function saveOffersFromNexus(array $offers)
    {

        $batchSize = 0;
        $batchLimit = 20;

        $status = $this->getOfferStatus(OfferStatus::ATTENTE_DE_VALIDATION);

        foreach ($offers as $offer) {
            $offer->setOfferStatus($status);
            $this->entityManager->persist($offer);
            $batchSize++;

            //flush in batches of $batchLimit to improve performance
            if ($batchSize == $batchLimit) {
                $this->entityManager->flush();
                $batchSize = 0;
            }
        }

        //call flush to flush any remaining offers
        $this->entityManager->flush();


    }


}   