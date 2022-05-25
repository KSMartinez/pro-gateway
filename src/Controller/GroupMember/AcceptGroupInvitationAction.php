<?php

namespace App\Controller\GroupMember;

use App\Entity\GroupMember;
use App\Service\GroupMemberService;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 *
 */
class AcceptGroupInvitationAction extends AbstractController
{

    /**
     * @param GroupMemberService $groupMemberService
     */
    public function __construct(private GroupMemberService $groupMemberService)
    {
    }

    /**
     * @param GroupMember $data
     * @return void
     * @throws Exception
     */
    public function __invoke(GroupMember $data)
    {
        $this->groupMemberService->acceptInvite($data);

    }
}