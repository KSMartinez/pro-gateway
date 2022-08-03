<?php

namespace App\DataPersister;

use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use App\Entity\Event;
use App\Entity\EventStatus;
use App\Entity\User;
use App\Service\GrantedService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;
use Symfony\Component\Security\Core\Security;

class EventDataPersister implements DataPersisterInterface
{

    public function __construct(
        private Security $security,
        private EntityManagerInterface $entityManager,
        private GrantedService $grantedService
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
        $eventStatus = $this->createEventStatus($data, $currentUser);
        $data->setEventStatus($eventStatus);
        $this->entityManager->persist($data);
        $this->entityManager->flush();

        return $data;
    }

    /**
     * @param Event $data
     * @param User $currentUser
     * @return EventStatus
     */
    private function createEventStatus(Event $data, User $currentUser): EventStatus
    {
        $eventStatus = new EventStatus();
        $eventStatus->setLabel(EventStatus::BROUILLON);
        if($this->grantedService->isGranted($currentUser, USER::ROLE_ADMIN) === true) {
            if($data->isPublic() === true) {
                $eventStatus->setLabel(EventStatus::PUBLIE);
            }
        }

        if($this->grantedService->isGranted($currentUser, USER::ROLE_ADMIN) === false) {
            if($data->isPublic() === true) {
                $eventStatus->setLabel(EventStatus::EN_ATTENTE);
            }
        }

        return $eventStatus;
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