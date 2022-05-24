<?php

namespace App\Service;
use App\Entity\Group;
use App\Entity\GroupStatus;
use App\Entity\User;
use App\Model\GroupDemand;
use App\Repository\GroupRepository;
use App\Repository\GroupStatusRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Exception;

class GroupService
{
    public function __construct(private EntityManagerInterface $entityManager, private GroupRepository $groupRepository, private GroupStatusRepository $groupStatusRepository)
    {
    }

    /**
     * @return Group[]
     * @throws Exception
     */
    public function getGroupDemands(): array
    {
        $groupDemandStatus = $this->getGroupStatus(GroupStatus::EN_ATTENTE);
        return $this->groupRepository->findBy(array('groupStatus' => $groupDemandStatus));
    }

    /**
     * @param GroupDemand $groupDemand
     * @return Group
     * @throws Exception
     * //todo notification for validating group
     */
    public function validateGroupDemand(GroupDemand $groupDemand): Group
    {
        $groupConfirmedStatus = $this->getGroupStatus(GroupStatus::CONFIRME);
        $group = $groupDemand->getGroup();
        $group->setGroupStatus($groupConfirmedStatus);

        if (!$group->getCreatedBy()) {
            throw new Exception("Group createdBy is null. Cannot notify user");
        }
//        $this->notificationService->createNotification($groupDemand->getNotificationMessage(),
//                                                       NotificationSource::GROUP_DEMAND, $group->getCreatedBy());
        $this->entityManager->persist($group);
        $this->entityManager->flush();
        return $group;
    }

    /**
     * @param GroupDemand $groupDemand
     * @return Group
     * @throws Exception
     * //todo notification for rejecting group
     */
    public function rejectGroupDemand(GroupDemand $groupDemand): Group
    {
        $groupRefusedStatus = $this->getGroupStatus(GroupStatus::REFUSE);

        $group = $groupDemand->getGroup();
        $group->setGroupStatus($groupRefusedStatus);
        $this->entityManager->persist($group);
        $this->entityManager->flush();
        return $group;
    }

    /**
     * @param Group $group
     * @param User  $user
     * @return Group
     * @throws Exception
     * //todo notification for new group
     */
    public function createNewGroupDemand(Group $group, User $user): Group
    {
        $groupEnAttentStatus = $this->getGroupStatus(GroupStatus::EN_ATTENTE);

        if (empty($group->getName())){
            throw new Exception('Name for a group is mandatory');
        }
        if ($this->checkGroupWithNameExists($group->getName())){
            throw new Exception('Group with this name already exists');
        }

        $group->setDateCreated(new DateTime('now'))
              ->setCreatedBy($user)
              ->setGroupStatus($groupEnAttentStatus);

        $this->entityManager->persist($group);
        $this->entityManager->flush();
        return $group;



    }

    /**
     * @param string $status
     * @return GroupStatus
     * @throws Exception
     */
    private function getGroupStatus(string $status): GroupStatus
    {
        $groupStatus = $this->groupStatusRepository->findOneBy(array('status' => $status));
        if (!$groupStatus) {
            throw new Exception('GroupStatus with label ' . $status . ' was not found in the table. Please add it correctly.');
        }
        return $groupStatus;
    }

    /**
     * @param string $name
     * @return bool
     */
    public function checkGroupWithNameExists(string $name): bool
    {

        $group = $this->groupRepository->findOneBy(['name' => $name]);
        return $group == null;
    }
}