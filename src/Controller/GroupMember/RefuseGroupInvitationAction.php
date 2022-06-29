<?php

namespace App\Controller\GroupMember;

use App\Entity\GroupMember;
use App\Entity\User;
use App\Service\GroupMemberService;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

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
     * @return GroupMember|Response
     * @throws Exception
     */
    public function __invoke(GroupMember $data): GroupMember|Response
    {
        /** @var User $currentUser */
        $currentUser = $this->getUser();
        if ($member = $this->groupMemberService->refuseInvite($data, $currentUser)) {
            return $member;
        }

        return new Response('Current user is not the same as the member', 403);

    }
}