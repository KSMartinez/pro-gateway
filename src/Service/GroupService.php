<?php

namespace App\Service;
use App\Entity\Group;
use App\Entity\GroupStatus;
use App\Model\GroupDemand;
use App\Repository\GroupRepository;
use App\Repository\GroupStatusRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;

class GroupService
{
    public function __construct(private EntityManagerInterface $entityManager, private GroupRepository $groupRepository, private GroupStatusRepository $groupStatusRepository)
    {
    }

    /**
     * @return Group[]
     */
    public function getGroupDemands(): array
    {
        $groupDemandStatus = $this->groupStatusRepository->findOneBy(array('status' => GroupStatus::EN_ATTENTE));
        return $this->groupRepository->findBy(array('groupStatus' => $groupDemandStatus));
    }

    /**
     * @param GroupDemand $groupDemand
     * @return Group
     * @throws Exception
     */
    public function validateGroupDemand(GroupDemand $groupDemand): Group
    {
        $groupConfirmedStatus = $this->groupStatusRepository->findOneBy(array('status' => GroupStatus::CONFIRME));
        if (!$groupConfirmedStatus){
            throw new Exception('GroupStatus with label ' . GroupStatus::CONFIRME . ' was not found in the table. Please add it correctly.');
        }
        $group = $groupDemand->getGroup();
        $group->setGroupStatus($groupConfirmedStatus);

        if (!$group->getCreatedBy()){
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
     */
    public function rejectGroupDemand(GroupDemand $groupDemand): Group
    {
        $groupRefusedStatus = $this->groupStatusRepository->findOneBy(array('status' => GroupStatus::REFUSE));
        if (!$groupRefusedStatus){
            throw new Exception('GroupStatu with label ' . GroupStatus::REFUSE . ' was not found in the table. Please add it correctly.');
        }
        $group = $groupDemand->getGroup();
        $group->setGroupStatus($groupRefusedStatus);
        $this->entityManager->persist($group);
        $this->entityManager->flush();
        return $group;
    }
}