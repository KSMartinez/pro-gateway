<?php

namespace App\Controller\Group;

use App\Entity\Group;
use App\Model\GroupDemand;
use App\Service\GroupService;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\AsController;

/**
 * Class RejectGroupDemandAction
 *
 * @package App\Controller\Group
 */
#[AsController]
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