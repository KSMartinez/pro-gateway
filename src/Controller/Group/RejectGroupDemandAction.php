<?php

namespace App\Controller\Group;

use App\Entity\Group;
use App\Model\GroupDemand;
use App\Service\GroupService;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Class RejectGroupDemandAction
 *
 * @package App\Controller\Group
 */
class RejectGroupDemandAction extends AbstractController
{

    public function __construct(private GroupService $groupService)
    {
    }


    /**
     * @param GroupDemand $data
     * @return Group
     * @throws Exception
     */
    public function __invoke(GroupDemand $data): Group
    {
        return $this->groupService->rejectGroupDemand($data);
    }
}