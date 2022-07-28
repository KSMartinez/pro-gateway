<?php

namespace App\DataPersister;

use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use App\Entity\Event;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;

class EventDataPersister implements DataPersisterInterface
{

    public function __construct(
        private Security $security,
        private EntityManagerInterface $entityManager
    )
    {
    }

    public function supports($data): bool
    {
        return $data instanceof Event;
    }

    /**
     * @param Event $data
     * @return Event
     */
    public function persist($data)
    {
        /** @var User $currentUser */
        $currentUser = $this->security->getUser();
        $data->setCreatedBy($currentUser);

        $this->entityManager->persist($data);
        $this->entityManager->flush();

        return $data;
    }

    /**
     * @param Event $data
     * @return void
     */
    public function remove($data): void
    {
        $this->entityManager->remove($data);
        $this->entityManager->flush();
    }
}