<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use App\Controller\Event\RandomEventsListAction;
use App\Controller\News\UpdateImageStockAction;
use App\Controller\News\UpdatePictureAction;
use App\Repository\NewsRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Serializer\Annotation\Groups;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use App\Validator\Constraints\News as Assert;
use Symfony\Component\Validator\Constraints as AssertVendor;

/**
 * @ApiFilter(OrderFilter::class,properties={"publishedAt":"ASC"})
 * @ApiFilter(SearchFilter::class, properties={
 *      "date":"partial",
 *      "category":"exact",
 *      "description":"partial",
 *      "title":"partial",
 *      "university":"exact"
 * })
 */
#[ORM\Entity(repositoryClass: NewsRepository::class)]
#[ApiResource(
    collectionOperations: [
        'get'=> [
            'normalization_context' => [
                'groups' => [
                    'news:read'
                ],
                'openapi_definition_name' => 'read collection'
            ]
        ],
        'post' => [
            'input_formats' => [
                'multipart' => ['multipart/form-data'],
            ],
        ],
        'randomEventsList' => [
            'method' => 'GET',
            'path' => '/randomEventsList',
            'controller' => RandomEventsListAction::class,
        ],
    ],
    iri: 'http://schema.org/News',
    itemOperations: [
        'get' => [
            'normalization_context' => [
                'groups' => [
                    'news:read',
                    'news:read:item'
                ],
                'openapi_definition_name' => 'read item'
            ]
        ],
        'put' => [
            'denormalization_context' => [
                'groups' => ['news:update'],
                'openapi_definition_name' => 'update item'
            ]
        ],
        'delete',
        'updatePicture' => [
            'method' => 'POST',
            'path' => '/news/{id}/updatePicture',
            'openapi_context' => [
                'summary' => 'Use this endpoint to update only the picture of the news. Use the PUT endpoint for all other updating'
            ],
            'controller' => UpdatePictureAction::class,
            'denormalization_context' => ['groups' => ['news:updatePicture']],
            'input_formats' => [
                'multipart' => ['multipart/form-data'],
            ]
        ],
        'updateImageStock' => [
            'method' => 'POST',
            'path' => '/news/{id}/updateImageStock',
            'openapi_context' => [
                'summary' => 'Use this endpoint to update only the picture of "banque d\'images"'
            ],
            'controller' => UpdateImageStockAction::class,
            'denormalization_context' => ['groups' => ['news:updateImageStock']],
            'input_formats' => [
                'json' => ['application/json'],
            ]
        ],
    ],
    denormalizationContext: ['groups' => ['news:create']],
    normalizationContext: [
        'groups' => [
            'news:read',
            'openapi_definition_name' => 'read collection'
        ]
    ])]
/**
 * @Vich\Uploadable()
 */
class News
{

    const VISIBILITY_PRIVATE = 'private';
    const VISIBILITY_PUBLIC = 'public';
    const ADMIN_VALIDATION_REQUEST_PENDING = 'pending';
    const ADMIN_VALIDATION_REQUEST_REJECTED = 'rejected';
    const ADMIN_VALIDATION_REQUEST_APPROVED = 'approved';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['news:read'])]
    private ?int $id = null;


    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\TitleRequirements]
    #[Groups(['news:read', 'news:create'])]
    private string $title;


    #[ORM\Column(type: 'text')]
    #[Assert\DescriptionRequirements]
    #[Groups(['news:read', 'news:create'])]
    private string $description;


    #[ORM\Column(type: 'boolean')]
    #[Groups(['news:read', 'news:create'])]
    private bool $forAllUniversities;


    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Groups(['news:read', 'news:create'])]
    private ?string $university;


    #[ORM\Column(type: 'datetime', nullable: true)]
    #[Groups(['news:read'])]
    private ?DateTimeInterface $publishedAt;

    #[ApiProperty(iri: 'http://schema.org/imageUrl')]
    #[Groups(['news:read'])]
    public ?string $imageUrl = null;


    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    public ?string $imagePath = null;


    /**
     * @Vich\UploadableField(mapping="media_object", fileNameProperty="imagePath")
     */
    #[Groups(['news:updatePicture', 'news:create'])]
    #[Assert\ImageFileRequirements]
    public ?File $imageFile = null;

    #[ORM\Column(
        type: 'string', length: 255, nullable: true,
        options: [
            'comment' => 'private or public',
            'default' => 'private'
        ]
    )]
    #[Assert\VisibilityRequirements]
    #[Groups(['news:read:item', 'news:create', 'news:update', 'news:create'])]
    private ?string $visibility;

    #[ORM\ManyToOne(targetEntity: NewsCategory::class, cascade: ['persist'], inversedBy: 'news')]
    #[Groups(['news:read', 'news:create', 'news:update'])]
    private ?NewsCategory $category;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?\DateTimeInterface $updatedAt;


    #[ORM\ManyToOne(targetEntity: User::class)]
    #[Groups(['news:read'])]
    private ?User $createdBy;

     /**
     * @var string[]
     */
    #[ORM\Column(type: 'json')]
    #[Groups(["news:read"])]
    private array $links = [];


    #[ORM\Column(type: 'text')]
    #[Assert\ChapoRequirements]
    #[Groups(['news:read', 'news:create'])]
    private string $chapo;

    #[ORM\Column(type: 'boolean', nullable: true,
        options: [
            'comment' => 'Is published if the request has been approved by the admin',
            'default' => false
        ]
    )]
    #[Groups(['news:read:item'])]
    private ?bool $published;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Groups(['news:read:item'])]
    private ?string $adminValidationRequest;

    #[ORM\Column(type: 'boolean', nullable: true,
        options: [
            'default' => false
        ]
    )]
    #[Groups(['news:create', 'news:update'])]
    private ?bool $publishInMyName;

    #[ORM\Column(type: 'boolean', nullable: true, options: ['comment' => 'Would like to publish', 'default' => false])]
    #[Groups(['news:read:item', 'news:create', 'news:update'])]
    private ?bool $wantToPublish;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?\DateTimeInterface $createdAt;

    public function __construct()
    {
        $this->publishedAt = new DateTime();
        $this->createdAt = new Datetime();
        $this->wantToPublish = false;
        $this->visibility = self::VISIBILITY_PRIVATE;
        $this->published = false;
        $this->publishInMyName = false;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function isForAllUniversities(): ?bool
    {
        return $this->forAllUniversities;
    }

    public function setForAllUniversities(bool $forAllUniversities): self
    {
        $this->forAllUniversities = $forAllUniversities;

        return $this;
    }

    public function getUniversity(): ?string
    {
        return $this->university;
    }

    public function setUniversity(?string $university): self
    {
        $this->university = $university;

        return $this;
    }

    /**
     * @return File|null
     */
    public function getFile(): ?File
    {
        return $this->imageFile;
    }

    /**
     * @param File|null $imageFile
     */
    public function setFile(?File $imageFile): void
    {
        $this->imageFile = $imageFile;
    }

    public function getCategory(): ?NewsCategory
    {
        return $this->category;
    }

    public function setCategory(NewsCategory $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getCreatedBy(): ?User
    {
        return $this->createdBy;
    }

    public function setCreatedBy(?User $createdBy): self
    {
        $this->createdBy = $createdBy;

        return $this;

    }

     /**
     * @return string[]
     */
    public function getLinks(): array
    {
        $links = $this->links;

        return array_unique($links);
    }

    /**
     * @param string[] $links
     * @return $this
     */
    public function setLink(array $links): self
    {
        $this->links = $links;

        return $this;
    }


    public function getChapo(): ?string
    {
        return $this->chapo;
    }

    public function setChapo(string $chapo): self
    {
        $this->chapo = $chapo;

        return $this;
    }

    public function getVisibility(): ?string
    {
        return $this->visibility;
    }

    public function setVisibility(?string $visibility): self
    {
        $this->visibility = $visibility;

        return $this;
    }

    public function isWantToPublish(): ?bool
    {
        return $this->wantToPublish;
    }

    public function setWantToPublish(?bool $wantToPublish): self
    {
        $this->wantToPublish = $wantToPublish;

        if ($this->wantToPublish) {
            $this->setAdminValidationRequest(self::ADMIN_VALIDATION_REQUEST_PENDING);
        }

        if (!$this->wantToPublish) {
            $this->setPublished(false);
            $this->setAdminValidationRequest(null);
        }

        return $this;
    }

    public function isPublished(): ?bool
    {
        return $this->published;
    }

    public function getPublishedAt(): ?\DateTimeInterface
    {
        return $this->publishedAt;
    }

    public function setPublishedAt(?\DateTimeInterface $publishedAt): self
    {
        $this->publishedAt = $publishedAt;

        return $this;
    }

    public function setAdminValidationRequest(?string $adminValidationRequest): self
    {
        $this->adminValidationRequest = $adminValidationRequest;

        if ($this->isApproved() && $this->wantToPublish) {
            $this->setPublished(true);
        }

        if ($this->isRejected() && $this->wantToPublish) {
            $this->setPublished(false);
            $this->setWantToPublish(null);
        }

        if ($this->isPending() && $this->wantToPublish) {
            $this->setPublished(false);
        }

        return $this;
    }

    private function isApproved(): bool
    {
        return $this->adminValidationRequest === self::ADMIN_VALIDATION_REQUEST_APPROVED;
    }

    private function setPublished(?bool $published): self
    {
        $this->published = $published;

        return $this;
    }

    private function isRejected(): bool
    {
        return $this->adminValidationRequest === self::ADMIN_VALIDATION_REQUEST_REJECTED;
    }

    private function isPending(): bool
    {
        return $this->adminValidationRequest === self::ADMIN_VALIDATION_REQUEST_PENDING;
    }

    public function getImagePath(): ?string
    {
        return $this->imagePath;
    }

    public function setImagePath(?string $imagePath): self
    {
        $this->imagePath = $imagePath;

        return $this;
    }
}
