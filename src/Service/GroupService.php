<?php

namespace App\Service;
use App\Entity\Group;
use App\Entity\GroupStatus;
use App\Repository\GroupRepository;
use App\Repository\GroupStatusRepository;

class GroupService
{

    public function __construct(private GroupRepository $groupRepository, private GroupStatusRepository $groupStatusRepository)
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
}