<?php

namespace App\DataPersister;

use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use App\Entity\Offer;
use App\Service\OfferService;
use Exception;

/**
 *
 */
class OfferDataPersister implements ContextAwareDataPersisterInterface
{

    /**
     * @param OfferService $offerService
     */
    public function __construct(private OfferService $offerService)
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
     * @return void
     * @throws Exception
     */
    public function persist($data, array $context = [])
    {
        // if the offer is being edited, we need to make it wait for validation again
        if (($context['item_operation_name'] ?? null) === 'put') {
            $this->offerService->reactivateOffer($data);
        }
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