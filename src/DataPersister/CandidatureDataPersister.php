<?php

namespace App\DataPersister;

use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use App\Entity\Candidature;
use App\Event\CandidatureCreatedEvent;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class CandidatureDataPersister implements DataPersisterInterface
{

    public function __construct(private EventDispatcherInterface $eventDispatcher, private EntityManagerInterface $entityManager)
    {
    }

    /**
     * @param mixed $data
     * @return bool
     */
    public function supports($data): bool
    {
        return $data instanceof Candidature;
    }

    /**
     * @param Candidature $data
     * @return Candidature
     */
    public function persist($data): Candidature
    {
        $isNew = false;
        //new candidature being created
        if ($data->getId() === null) {
            $isNew = true;
        }

        $this->entityManager->persist($data);
        $this->entityManager->flush();

        if ($isNew) {
            $event = new CandidatureCreatedEvent($data);
            $this->eventDispatcher->dispatch($event, CandidatureCreatedEvent::NAME);
        }

        return $data;
    }

    /**
     * @param Candidature $data
     * @return void
     */
    public function remove($data)
    {
        $this->entityManager->remove($data);
        $this->entityManager->flush();
    }
}