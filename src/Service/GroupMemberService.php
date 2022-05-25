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
        $groupMemberStatus = $this->getGroupMemberStatus(GroupMemberStatus::INVITE);

        $groupMember->setGroupMemberStatus($groupMemberStatus);

        $this->entityManager->persist($groupMember);
        $this->entityManager->flush();

        return $groupMember;
    }

    /**
     * @param GroupMember $groupMember
     * @throws Exception
     * @return void
     */
    public function acceptInvite(GroupMember $groupMember)
    {

        $groupMemberStatus = $this->getGroupMemberStatus(GroupMemberStatus::ACTIF);
        $groupMember->setGroupMemberStatus($groupMemberStatus);

        $groupMember->setGroupMemberStatus($groupMemberStatus);
        $this->entityManager->persist($groupMember);
        $this->entityManager->flush();

    }

    /**
     * @param string $status
     * @return GroupMemberStatus
     * @throws Exception
     */
    private function getGroupMemberStatus(string $status): GroupMemberStatus
    {

        $groupMemberStatus = $this->groupMemberStatusRepository->findOneBy(['status' => $status]);

        if (!$groupMemberStatus) {
            throw new Exception('GroupMemberStatus with status ' . $status . ' not found. Please add this to the table');
        }

        return $groupMemberStatus;

    }

    /**
     * @param GroupMember $groupMember
     * @return void
     * @throws Exception
     */
    public function refuseInvite(GroupMember $groupMember)
    {
        $groupMemberStatus = $this->getGroupMemberStatus(GroupMemberStatus::REFUSE);
        $groupMember->setGroupMemberStatus($groupMemberStatus);

        $groupMember->setGroupMemberStatus($groupMemberStatus);
        $this->entityManager->persist($groupMember);
        $this->entityManager->flush();
    }

}