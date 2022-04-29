<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Controller\CV\UpdateCVAction;
use App\Repository\CVRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
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
       'get', 'delete','put',
       'updateFile' => [
           'method' => 'POST',
           'path' => '/cvs/{id}/update',
           'openapi_context' => [
               'summary'     => 'Use this endpoint to update only the file of the CV. Use the PUT endpoint for all other updating',
               'description' => "# Pop a great rabbit picture by color!\n\n![A great rabbit]"
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
       shortName: "cvs", denormalizationContext: [
           'groups' => [
               'cv:write', 'cv:update'
           ]
       ], normalizationContext: [
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
    #[Groups(["cv:read", "cv:write"])]
    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $experience;

    /**
     * @var string|null
     */
    #[Groups(["cv:read", "cv:write"])]
    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $education;

    /**
     * @var string|null
     */
    #[Groups(["cv:read", "cv:write"])]
    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $skills;

    /**
     * @var string|null
     */
    #[Groups(["cv:read"])]
    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $fileLink = "";

    #[ApiProperty(iri: 'http://schema.org/contentUrl')]
    #[Groups(['cv:read'])]
    public ?string $contentUrl = null;


    /**
     * @Vich\UploadableField(mapping="media_object", fileNameProperty="fileLink")
     */
    #[Groups(['cv:write', 'cv:update'])]
    public ?File $file = null;

    #[Groups(["cv:read", "cv:write"])]
    #[ORM\OneToOne(inversedBy: 'cV', targetEntity: User::class, cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private User $user;

    /**
     * This field is techincally not needed. But we need this to update the file of the CV.
     * This is a bug with VichUploaderBundle. Refer this link
     * https://github.com/dustin10/VichUploaderBundle/issues/8
     * @var DateTimeInterface
     */
    #[ORM\Column(type: 'date')]
    private DateTimeInterface $updatedAt;

    public function __construct()
    {
        $this->updatedAt = new DateTime('now');
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
    public function getExperience(): ?string
    {
        return $this->experience;
    }

    /**
     * @param string|null $experience
     * @return $this
     */
    public function setExperience(?string $experience): self
    {
        $this->experience = $experience;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getEducation(): ?string
    {
        return $this->education;
    }

    /**
     * @param string|null $education
     * @return $this
     */
    public function setEducation(?string $education): self
    {
        $this->education = $education;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getSkills(): ?string
    {
        return $this->skills;
    }

    /**
     * @param string|null $skills
     * @return $this
     */
    public function setSkills(?string $skills): self
    {
        $this->skills = $skills;
        return $this;
    }

    public function getFileLink(): ?string
    {
        return $this->fileLink;
    }

    public function setFileLink(?string $fileLink): self
    {
        $this->fileLink = $fileLink;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getUpdatedAt(): ?DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
}
