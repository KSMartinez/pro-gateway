<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Controller\GroupMember\InviteGroupMemberAction;
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
            'controller' => InviteGroupMemberAction::class
        ]
    ],
    itemOperations        : [
        'get', 'put', 'delete'
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
    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'groupsMemberOf')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(["group_member.read", "group_member.write"])]
    private User $user;

    /**
     * @var string[]|null
     */
    #[ORM\Column(type: 'json', nullable: true)]
    #[Groups(["group_member.read"])]
    private ?array $roles = [];

    /**
     * @var GroupMemberStatus|null
     */
    #[ORM\ManyToOne(targetEntity: GroupMemberStatus::class)]
    #[Groups(["group_member.read", "group_member.write"])]
    private ?GroupMemberStatus $groupMemberStatus;

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
     * @return string[] | null
     */
    public function getRoles(): ?array
    {
        $roles = $this->roles;
        $roles[] = GroupMember::ROLE_GROUP_USER;
        return array_unique($roles);
    }

    /**
     * @param string[]|null $roles
     * @return $this
     */
    public function setRoles(?array $roles): self
    {
        $this->roles = $roles;

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
}
