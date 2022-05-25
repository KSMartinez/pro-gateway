<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Controller\CV\UpdateCVAction;
use App\Repository\CVRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Serializer\Annotation\Groups;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @Vich\Uploadable
 */
#[ORM\Entity(repositoryClass: CVRepository::class)]
#[ApiResource(
    collectionOperations: [
        'get',
        'post' => [
            'input_formats' => [
                'multipart' => ['multipart/form-data'],
            ],

        ],
    ],
    itemOperations      : [
        'get', 'delete', 'put',
        'updateFile' => [
            'method' => 'POST',
            'path' => '/cvs/{id}/update',
            'openapi_context' => [
                'summary' => 'Use this endpoint to update only the file of the CV. Use the PUT endpoint for all other updating'
            ],
            'controller' => UpdateCVAction::class,
            'denormalization_context' => ['groups' => ['cv:update']],
            'input_formats' => [
                'multipart' => ['multipart/form-data'],
            ]

        ],
        'patch' => [
            'input_formats' => [
                'multipart' => ['multipart/form-data'],
            ]
        ]
    ],
    shortName           : "cvs", denormalizationContext: [
    'groups' => [
        'cv:write', 'cv:update'
    ]
],  normalizationContext: [
    "groups" => [
        "cv:read"
    ]
]


)]
class CV
{
    /**
     * @var int|null
     */
    #[Groups(["cv:read", "cv:write"])]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id;


    /**
     * @var string|null
     */
    #[ApiProperty(iri: 'http://schema.org/contentUrl')]
    #[Groups(['cv:read'])]
    public ?string $contentUrl = null;
    
    /**
     * @var Collection<int, Education>
     */
    #[ORM\OneToMany(mappedBy: 'cv', targetEntity: Education::class)]
    public Collection $educations;
    /**
     * @var Collection<int, Experience>
     */
    #[ORM\OneToMany(mappedBy: 'cv', targetEntity: Experience::class)]
    public Collection $experiences;
    /**
     * @Vich\UploadableField(mapping="media_object", fileNameProperty="fileLink")
     */
    #[Groups(['cv:write', 'cv:update'])]
    public ?File $file = null;

    /**
     * @var string|null
     */
    #[Groups(["cv:read"])]
    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $fileLink = "";
    /**
     * @var User
     */
    #[Groups(["cv:read", "cv:write"])]
    #[ORM\OneToOne(inversedBy: 'cV', targetEntity: User::class, cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private User $user;
    /**
     * This field is technically not needed. But we need this to update the file of the CV.
     * This is a bug with VichUploaderBundle. Refer this link
     * https://github.com/dustin10/VichUploaderBundle/issues/8
     *
     * @var DateTimeInterface
     */
    #[ORM\Column(type: 'date')]
    private DateTimeInterface $updatedAt;

    /**
     *
     */
    public function __construct()
    {
        $this->updatedAt = new DateTime('now');
        $this->educations = new ArrayCollection();
        $this->experiences = new ArrayCollection();
    }

    /**
     * @return Collection<int, Education>
     */
    public function getEducations(): Collection
    {
        return $this->educations;
    }


    /**
     * @param Collection<int, Education> $educations
     * @return $this
     */
    public function setEducations(Collection $educations): CV
    {
        $this->educations = $educations;
        return $this;
    }

    /**
     * @return Collection<int,Experience>
     */
    public function getExperiences(): Collection
    {
        return $this->experiences;
    }


    /**
     * @param Collection<int, Experience> $experiences
     * @return $this
     */
    public function setExperiences(Collection $experiences): CV
    {
        $this->experiences = $experiences;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getContentUrl(): ?string
    {
        return $this->contentUrl;
    }

    /**
     * @param string|null $contentUrl
     */
    public function setContentUrl(?string $contentUrl): void
    {
        $this->contentUrl = $contentUrl;
    }

    /**
     * @return File|null
     */
    public function getFile(): ?File
    {
        return $this->file;
    }

    /**
     * @param File|null $file
     */
    public function setFile(?File $file): void
    {
        $this->file = $file;
    }


    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }


    /**
     * @return string|null
     */
    public function getFileLink(): ?string
    {
        return $this->fileLink;
    }

    /**
     * @param string|null $fileLink
     * @return $this
     */
    public function setFileLink(?string $fileLink): self
    {
        $this->fileLink = $fileLink;

        return $this;
    }

    /**
     * @return User|null
     */
    public function getUser(): ?User
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
     * @return DateTimeInterface|null
     */
    public function getUpdatedAt(): ?DateTimeInterface
    {
        return $this->updatedAt;
    }

    /**
     * @param DateTimeInterface $updatedAt
     * @return $this
     */
    public function setUpdatedAt(DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

}
