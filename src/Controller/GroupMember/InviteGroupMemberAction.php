<?php

namespace App\Controller\GroupMember;

use App\Entity\GroupMember;
use App\Service\GroupMemberService;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\AsController;

/**
 * Class InviteGroupMemberAction
 *
 * @package App\Controller\GroupMember
 */
#[AsController]
class InviteGroupMemberAction extends AbstractController
{
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
        return $this->groupMemberService->createInvitationForGroupMember($data);
    }


}