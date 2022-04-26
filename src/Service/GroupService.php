<?php

namespace App\Service;
use App\Entity\Group;
use App\Entity\GroupStatus;
use App\Entity\NotificationSource;
use App\Model\GroupDemand;
use App\Repository\GroupRepository;
use App\Repository\GroupStatusRepository;

class GroupService
{
    public function __construct(private GroupRepository $groupRepository, private GroupStatusRepository $groupStatusRepository, private NotificationService $notificationService)
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
     */
    public function validateGroupDemand(GroupDemand $groupDemand): Group
    {
        $groupConfirmedStatus = $this->groupStatusRepository->findOneBy(array('status' => GroupStatus::CONFIRME));
        $groupDemand->getGroup()->setGroupStatus($groupConfirmedStatus);
        $this->notificationService->createNotification($groupDemand->getNotificationMessage(), NotificationSource::GROUP_DEMAND);
        return $groupDemand;
    }

    public function rejectGroupDemand(Group $groupDemand): Group
    {
        $groupRefusedStatus = $this->groupStatusRepository->findOneBy(array('status' => GroupStatus::REFUSE));
        $groupDemand->setGroupStatus($groupRefusedStatus);
        return $groupDemand;
    }
}