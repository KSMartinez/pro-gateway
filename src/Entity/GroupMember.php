<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Controller\GroupMember\AcceptGroupInvitationAction;
use App\Controller\GroupMember\InviteGroupMemberAction;
use App\Controller\GroupMember\RefuseGroupInvitationAction;
use App\Repository\GroupMemberRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 *
 */
#[ORM\Entity(repositoryClass: GroupMemberRepository::class)]
#[ApiResource(
    collectionOperations  : [
        'get',
        'invite_group_member' => [
            'method' => 'POST',
            'path' => '/group-members/invite',
            'status' => 200,
            'controller' => InviteGroupMemberAction::class
        ]
    ],
    itemOperations        : [
        'get', 'put', 'delete',
        'accept_invitation_group_member' => [
            'method' => 'POST',
            'path' => '/group-members/invite/{id}/accept',
            'status' => 200,
            'controller' => AcceptGroupInvitationAction::class
        ],
        'refuse_invitation_group_member' => [
            'method' => 'POST',
            'path' => '/group-members/invite/{id}/refuse',
            'status' => 200,
            'controller' => RefuseGroupInvitationAction::class
        ]
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
        $this->memberRoles = $memberRoles;

        return $this;
    }
}
