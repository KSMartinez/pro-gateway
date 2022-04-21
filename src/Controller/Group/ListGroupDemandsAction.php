<?php

namespace App\Controller\Group;
use App\Entity\Group;
use App\Service\GroupService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\AsController;

/**
 * Class ListGroupDemandsAction
 * @package App\Controller\Group
 */
#[AsController]
class ListGroupDemandsAction extends AbstractController
{

    public function __construct(private GroupService $groupService)
    {
    }


    /**
     * @return Group[]
     */
    public function __invoke(): array
    {
        return $this->groupService->getGroupDemands();
    }
}