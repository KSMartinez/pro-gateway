<?php

namespace App\DataPersister;

use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use App\Entity\Offer;
use App\Entity\User;
use App\Service\OfferService;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\Security\Core\Security;

/**
 *
 */
class OfferDataPersister implements ContextAwareDataPersisterInterface
{

    /**
     * @param OfferService $offerService
     * @param Security $security
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(
        private OfferService           $offerService,
        private Security               $security,
        private EntityManagerInterface $entityManager
    )
    {

    }

    /**
     * @param mixed $data
     * @param array<mixed> $context
     * @return bool
     */
    public function supports($data, array $context = []): bool
    {
        return $data instanceof Offer;
    }

    /**
     * @param Offer $data
     * @param array<mixed> $context
     * @return Offer
     * @throws Exception
     */
    public function persist($data, array $context = []): Offer
    {
        /** @var User $currentUser */
        $currentUser = $this->security->getUser();
        $data->setCreatedByUser($currentUser);
        $this->entityManager->persist($data);
        $this->entityManager->flush();

        // if the offer is being edited, we need to make it wait for validation again
        if (($context['item_operation_name'] ?? null) === 'put') {
            $this->offerService->reactivateOffer($data);
        }

        return $data;
    }

    /**
     * @param Offer $data
     * @param array<mixed> $context
     * @return void
     */
    public function remove($data, array $context = [])
    {
        //delete is implemented as soft delete in custom operation
//        $this->entityManager->remove($data);
//        $this->entityManager->flush();
    }
}