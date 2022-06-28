<?php

namespace App\Controller\GroupMember;

use App\Entity\GroupMember;
use App\Entity\User;
use App\Service\GroupMemberService;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;

#[AsController]
class IgnoreRequestToJoinGroupAction extends AbstractController
{
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
        /** @var User $user */
        $user = $this->getUser();
        if ($this->groupMemberService->isGroupAdmin($user, $data->getGroupOfMember())) {
            return $this->groupMemberService->ignoreRequestToJoinGroup($data);
        }

        return new Response("Not group admin", 403);
    }


}