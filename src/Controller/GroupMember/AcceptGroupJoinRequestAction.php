<?php

namespace App\Controller\GroupMember;

use App\Entity\GroupMember;
use App\Entity\User;
use App\Service\GroupMemberService;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\BrowserKit\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;

#[AsController]
class AcceptGroupJoinRequestAction extends AbstractController
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
            return $this->groupMemberService->acceptRequestToJoin($data);
        } else {
            return new Response('Not group admin', 403);
        }
    }


}