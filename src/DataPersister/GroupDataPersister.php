<?php

namespace App\DataPersister;

use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use App\Entity\Group;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;

class GroupDataPersister implements DataPersisterInterface
{

    public function __construct(
        private Security               $security,
        private EntityManagerInterface $entityManager
    )
    {
    }

    /**
     * @inheritDoc
     */
    public function supports($data): bool
    {
        return $data instanceof Group;
    }

    /**
     * @param Group $data
     * @return Group
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
     * @param Group $data
     * @return void
     */
    public function remove($data)
    {
        $this->entityManager->remove($data);
        $this->entityManager->flush();
    }
}