<?php

namespace App\Service;

use App\Entity\Group;
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
     * @return GroupMember
     *@throws Exception
     */
    public function acceptInvite(GroupMember $groupMember): GroupMember
    {

        $groupMemberStatus = $this->getGroupMemberStatus(GroupMemberStatus::ACTIF);
        $groupMember->setGroupMemberStatus($groupMemberStatus);

        $groupMember->setGroupMemberStatus($groupMemberStatus);
        $this->entityManager->persist($groupMember);
        $this->entityManager->flush();

        return $groupMember;

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
     * @return GroupMember
     * @throws Exception
     */
    public function refuseInvite(GroupMember $groupMember): GroupMember
    {
        $groupMemberStatus = $this->getGroupMemberStatus(GroupMemberStatus::REFUSE);
        $groupMember->setGroupMemberStatus($groupMemberStatus);

        $groupMember->setGroupMemberStatus($groupMemberStatus);
        $this->entityManager->persist($groupMember);
        $this->entityManager->flush();
        return $groupMember;
    }

    /**
     * @param Group $group
     * @return GroupMember[]
     */
    public function getGroupMemberAdmins(Group $group): array
    {

        $members = $group->getGroupMembers();
        $admins = [];

        foreach ($members as $member) {
            if (in_array(GroupMember::ROLE_GROUP_ADMIN, $member->getUser()->getRoles())){
                $admins[] = $member;
            }
        }

        return $admins;
    }

}