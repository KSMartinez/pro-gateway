<?php

namespace App\Controller\GroupMember;

use App\Entity\GroupMember;
use App\Entity\User;
use App\Service\GroupMemberService;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

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
     * @return GroupMember|Response
     * @throws Exception
     */
    public function __invoke(GroupMember $data): GroupMember|Response
    {

        /** @var User $currentUser */
        $currentUser = $this->getUser();
        if ($member = $this->groupMemberService->acceptInvite($data, $currentUser)) {
            return $member;
        } else {
            return new Response("Not allowed. Current user is not the same as the one in the group member", 403);
        }


    }
}