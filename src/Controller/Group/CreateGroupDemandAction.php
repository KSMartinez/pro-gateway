<?php

namespace App\Controller\Group;

use App\Entity\Group;
use App\Entity\User;
use App\Service\GroupService;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\AsController;

/**
 * Class CreateGroupDemandAction
 *
 * @package App\Controller\Group
 */
#[AsController]
class CreateGroupDemandAction extends AbstractController
{
    /**
     * @param GroupService $groupService
     */
    public function __construct(private GroupService $groupService)
    {
    }


    /**
     * @param Group $data
     * @return Group
     * @throws Exception
     */
    public function __invoke(Group $data): Group
    {
        /** @var User $user */
        $user = $this->getUser();
        return $this->groupService->createNewGroupDemand($data, $user);
    }
}