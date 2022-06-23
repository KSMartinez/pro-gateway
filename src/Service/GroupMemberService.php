<?php

namespace App\Service;

use App\Entity\Group;
use App\Entity\GroupMember;
use App\Entity\GroupMemberStatus;
use App\Entity\User;
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


        $groupMember->setMemberRoles([GroupMember::ROLE_GROUP_USER]);

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
            if (in_array(GroupMember::ROLE_GROUP_ADMIN, $member->getUser()
                                                               ->getRoles())) {
                $admins[] = $member;
            }
        }

        return $admins;
    }

    /**
     * @param GroupMember $member
     * @param User        $currentUser
     * @throws Exception
     */
    public function removeUserFromGroup(GroupMember $member, User $currentUser): void
    {


        $userBeingDeleted = $member->getUser();
        // if current currentUser is not admin, make some checks
        if (!in_array(User::ROLE_ADMIN, $currentUser->getRoles())) {

            //if user being deleted is a group admin
            if (in_array(GroupMember::ROLE_GROUP_ADMIN, $member->getMemberRoles())) {

                // the current user is user being deleted, can't delete [CANNOT DELETE OTHER ADMINS FROM GROUP]
                if ($userBeingDeleted !== $currentUser) {
                    throw new Exception('Cannot delete a group admin from the group');
                } // If the user is deleting themselves and they're the last admin, cannot delete [CANNOT DELETE ONESELF IF LAST ADMIN]
                else if (count($this->getGroupMemberAdmins($member->getGroupOfMember())) == 1) {
                    throw new Exception('Cannot remove user who is the last admin user. No more admins left if this user is removed.');
                }
            }
        }

        $this->entityManager->remove($member);
        $this->entityManager->flush();
    }

}