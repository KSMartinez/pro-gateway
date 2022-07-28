<?php

namespace App\DataPersister;

use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use App\Entity\GroupMember;
use App\Entity\User;
use App\Repository\GroupMemberRepository;
use App\Service\GroupMemberService;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\Security\Core\Security;

/**
 *
 */
class GroupMemberDataPersister implements DataPersisterInterface
{

    public function __construct(
        private EntityManagerInterface $entityManager,
        private Security $security,
        private GroupMemberService $groupMemberService
    )
    {
    }

    /**
     * @param mixed $data
     * @return bool
     */
    public function supports($data): bool
    {
        return $data instanceof GroupMember;
    }

    /**
     * @param GroupMember $data
     * @return GroupMember
     */
    public function persist($data): GroupMember
    {
        $this->entityManager->persist($data);
        $this->entityManager->flush();
        return $data;
    }

    /**
     * @param GroupMember $data
     * @return void
     * @throws Exception
     */
    public function remove($data)
    {
        /** @var User $currentUser */
        $currentUser = $this->security->getUser();
        $this->groupMemberService->removeUserFromGroup($data, $currentUser);

    }
}