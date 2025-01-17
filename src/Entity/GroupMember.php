<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Controller\GroupMember\AcceptGroupInvitationAction;
use App\Controller\GroupMember\AcceptGroupJoinRequestAction;
use App\Controller\GroupMember\IgnoreRequestToJoinGroupAction;
use App\Controller\GroupMember\InviteGroupMemberAction;
use App\Controller\GroupMember\MakeMemberGroupAdminAction;
use App\Controller\GroupMember\RefuseGroupInvitationAction;
use App\Controller\GroupMember\RemoveAdminRightsFromMemberAction;
use App\Controller\GroupMember\RequestToJoinGroupAction;
use App\Repository\GroupMemberRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;

/**
 * @ApiFilter(SearchFilter::class, properties={"groupOfMember":"exact", "user": "exact", "groupMemberStatus": "exact"})
 */
#[ORM\Entity(repositoryClass: GroupMemberRepository::class)]
#[ApiResource(
    collectionOperations  : [
        'get',
        'invite_group_member' => [
            'method' => 'POST',
            'path' => '/group-members/invite',
            'openapi_context' => [
                'summary'     => 'Invite a user to join the group by setting the user and the group in the GroupMember'
            ],
            'status' => 200,
            'controller' => InviteGroupMemberAction::class
        ],
        'request_join_group' => [
            'method' => 'POST',
            'path' => '/group-members/join/',
            'openapi_context' => [
                'summary'     => 'A user can request to join the group. Set the user and group in the GroupMember'
            ],
            'status' => 200,
            'controller' => RequestToJoinGroupAction::class
        ],
    ],
    itemOperations        : [
        'get', 'put', 'delete',
        'accept_invitation_group_member' => [
            'method' => 'POST',
            'path' => '/group-members/invite/{id}/accept',
            'openapi_context' => [
                'summary'     => 'A group member can accept the invitation. Set the correct id of the GroupMember object.'
            ],
            'status' => 200,
            'controller' => AcceptGroupInvitationAction::class
        ],
        'refuse_invitation_group_member' => [
            'method' => 'POST',
            'openapi_context' => [
                'summary'     => 'User can refuse the invite and choose not to join the group. The status will be set to refuse.'
            ],
            'path' => '/group-members/invite/{id}/refuse',
            'status' => 200,
            'controller' => RefuseGroupInvitationAction::class
        ],
        'make_member_group_admin' => [
            'method' => 'POST',
            'path' => '/group-members/admin/promote/{id}',
            'openapi_context' => [
                'summary'     => 'A group member can promote another group member to group admin. This action can only be performed by group admins (will return 403 if not group admin).'
            ],
            'status' => 200,
            'controller' => MakeMemberGroupAdminAction::class
        ],
        //todo add ROLE_ADMIN to access control
        'remove_admin_rights_from_member' => [
            'method' => 'POST',
            'openapi_context' => [
                'summary'     => 'Remove the admin capability of a group member. This action can only be done by an ADMINISTRATOR (NOT a group admin. A site administrator)'
            ],
            'path' => '/group-members/admin/demote/{id}',
            'status' => 200,
            'controller' => RemoveAdminRightsFromMemberAction::class
        ],
        'accept_group_join_request' => [
            'method' => 'POST',
            'path' => '/group-members/join/accept/{id}',
            'openapi_context' => [
                'summary'     => 'A group admin can accept the join request of a user. Will return 403 if user is not group admin.'
            ],
            'status' => 200,
            'controller' => AcceptGroupJoinRequestAction::class
        ],
        'ignore_group_join_request' => [
            'method' => 'POST',
            'path' => '/group-members/join/accept/{id}',
            'openapi_context' => [
                'summary'     => 'Ignore a join request from a user'
            ],
            'status' => 200,
            'controller' => IgnoreRequestToJoinGroupAction::class
        ],
    ],
    denormalizationContext: [
        'groups' => ['group_member.write']
    ],
    normalizationContext: [
        'groups' => ['group_member.read']
    ]
)]
class GroupMember
{

    const ROLE_GROUP_USER = "ROLE_GROUP_USER";
    const ROLE_GROUP_ADMIN = "ROLE_GROUP_ADMIN";

    /**
     * @var int|null
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    /**
     * @var Group
     */
    #[ORM\ManyToOne(targetEntity: Group::class, inversedBy: 'groupMembers')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(["group_member.read", "group_member.write"])]
    private Group $groupOfMember;

    /**
     * @var User
     */
    #[ORM\ManyToOne(targetEntity: User::class, cascade: ['persist'], inversedBy: 'groupsMemberOf')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(["group_member.read", "group_member.write"])]
    private User $user;


    /**
     * @var GroupMemberStatus|null
     */
    #[ORM\ManyToOne(targetEntity: GroupMemberStatus::class)]
    #[Groups(["group_member.read", "group_member.write"])]
    private ?GroupMemberStatus $groupMemberStatus;

    /**
     * @var string[]
     */
    #[ORM\Column(type: 'json', nullable: true)]
    private array $memberRoles = [];

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Group
     */
    public function getGroupOfMember(): Group
    {
        return $this->groupOfMember;
    }

    /**
     * @param Group $groupOfMember
     * @return $this
     */
    public function setGroupOfMember(Group $groupOfMember): self
    {
        $this->groupOfMember = $groupOfMember;

        return $this;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @param User $user
     * @return $this
     */
    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return GroupMemberStatus|null
     */
    public function getGroupMemberStatus(): ?GroupMemberStatus
    {
        return $this->groupMemberStatus;
    }

    /**
     * @param GroupMemberStatus|null $groupMemberStatus
     * @return $this
     */
    public function setGroupMemberStatus(?GroupMemberStatus $groupMemberStatus): self
    {
        $this->groupMemberStatus = $groupMemberStatus;

        return $this;
    }

    /**
     * @return string[]
     */
    public function getMemberRoles(): array
    {
        return $this->memberRoles;
    }

    /**
     * @param string[]|null $memberRoles
     * @return $this
     */
    public function setMemberRoles(?array $memberRoles): self
    {
        if ($memberRoles == null){
            $memberRoles = [];
        }
        $this->memberRoles = array_unique($memberRoles);

        return $this;
    }
}
