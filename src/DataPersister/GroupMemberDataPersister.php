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

    public function __construct(private EntityManagerInterface $entityManager, private Security $security, private GroupMemberService $groupMemberService)
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
        $userBeingDeleted  = $data->getUser();
        // if current currentUser is not admin, make some checks
        if (!in_array(User::ROLE_ADMIN, $currentUser->getRoles())){

            //if user being deleted is a group admin
            if (in_array(GroupMember::ROLE_GROUP_ADMIN, $userBeingDeleted->getRoles()) ){

                // the current user is user being deleted, can't delete [CANNOT DELETE OTHER ADMINS FROM GROUP]
                if ($userBeingDeleted !== $currentUser){
                    throw new Exception('Cannot delete a group admin from the group');
                } // If the user is deleting themselves and they're the last admin, cannot delete [CANNOT DELETE ONESELF IF LAST ADMIN]
                else if(count($this->groupMemberService->getGroupMemberAdmins($data->getGroupOfMember())) == 1){
                    throw new Exception('Cannot remove user who is the last admin user. No more admins left if this user is removed.');
                }
            }
        }

        $this->entityManager->remove($data);
        $this->entityManager->flush();


    }
}