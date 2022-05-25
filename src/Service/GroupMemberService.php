<?php

namespace App\Service;

use App\Entity\GroupMember;
use App\Entity\GroupMemberStatus;
use App\Repository\GroupMemberRepository;
use App\Repository\GroupMemberStatusRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;

class GroupMemberService
{

    public function __construct(private EntityManagerInterface $entityManager, private GroupMemberStatusRepository $groupMemberStatusRepository)
    {
    }

    /**
     * @param GroupMember $groupMember
     * @return GroupMember
     * @throws Exception
     */
    public function createInvitationForGroupMember(GroupMember $groupMember): GroupMember
    {
        $groupMemberStatus = $this->groupMemberStatusRepository->findOneBy(['status' => GroupMemberStatus::INVITE]);

        if (!$groupMemberStatus) {
            throw new Exception('GroupMemberStatus with status ' . GroupMemberStatus::INVITE . ' not found. Please add this to the table');
        }

        $groupMember->setGroupMemberStatus($groupMemberStatus);

        $this->entityManager->persist($groupMember);
        $this->entityManager->flush();

        return $groupMember;
    }

}