<?php

namespace App\Controller\GroupMember;

use App\Entity\GroupMember;
use App\Service\GroupMemberService;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RefuseGroupInvitationAction extends AbstractController
{

    /**
     * @param GroupMemberService $groupMemberService
     */
    public function __construct(private GroupMemberService $groupMemberService)
    {
    }

    /**
     * @param GroupMember $data
     * @return GroupMember
     * @throws Exception
     */
    public function __invoke(GroupMember $data): GroupMember
    {
        return $this->groupMemberService->refuseInvite($data);

    }
}