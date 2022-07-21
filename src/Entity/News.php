<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use App\Controller\Event\RandomEventsListAction;
use App\Controller\News\UpdatePictureAction;
use App\Repository\NewsRepository;
use DateTime;
use DateTimeImmutable;
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
    /**
     * @var int|null   
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['news:read'])]
    private ?int $id = null;

    /**
     * @var string    
     */
    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\TitleRequirements]
    #[Groups(['news:read', 'news:create'])]
    private string $title;

    /**
     * @var string    
     */
    #[ORM\Column(type: 'text')]
    #[Assert\DescriptionRequirements]
    #[Groups(['news:read', 'news:create'])]
    private string $description;

    /**
     * @var boolean     
     */
    #[ORM\Column(type: 'boolean')]
    #[Groups(['news:read', 'news:create'])]
    private bool $forAllUniversities;

    /**
     * @var string|null  
     */
    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Groups(['news:read', 'news:create'])]
    private ?string $university;

    /**
     * @var DateTimeImmutable
     */
    #[ORM\Column(type: 'datetime_immutable', nullable: false)]
    #[Groups(['news:read'])]
    private DateTimeImmutable $publishedAt;

    #[ApiProperty(iri: 'http://schema.org/imageUrl')]
    #[Groups(['news:read'])]
    public ?string $imageUrl = null;


    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[AssertVendor\Valid()]
    /*#[Groups(['news:read:item', 'news:create', 'news:update', 'news:updateImageStock'])]*/
    public ?string $imagePath = null;


    /**
     * @Vich\UploadableField(mapping="media_object", fileNameProperty="imagePath")
     */
    #[Groups(['news:updatePicture', 'news:create'])]
    #[Assert\ImageFileRequirements]
    public ?File $imageFile = null;

    #[ORM\Column(type: 'boolean')]
    #[Groups(['news:read', 'news:create'])]
    private bool $isPublic;

    #[ORM\ManyToOne(targetEntity: NewsCategory::class, cascade: ['persist'], inversedBy: 'news')]
    #[Groups(['news:read', 'news:create', 'news:update'])]
    private ?NewsCategory $category;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?\DateTimeInterface $updatedAt;

     /**
     * @var User|null
     */
    #[ORM\ManyToOne(targetEntity: User::class)]
    #[Groups(['news:read'])]
    private ?User $createdBy;

     /**
     * @var string[]
     */
    #[ORM\Column(type: 'json')]
    #[Groups(["news:read"])]
    private array $links = [];

     /**
     * @var string
     */
    #[ORM\Column(type: 'text')]
    #[Groups(['news:read', 'news:create'])]
    private string $chapo;

    public function __construct()
    {
        $this->publishedAt = new DateTimeImmutable();
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

    public function getForAllUniversities(): ?bool
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
     * @return DateTimeImmutable 
     */
    public function getPublishedAt(): \DateTimeImmutable
    {
        return $this->publishedAt;
    }

    /**
     * @param DateTimeImmutable $publishedAt
     * @return $this     
     */
    public function setPublishedAt(\DateTimeImmutable $publishedAt): self
    {
        $this->publishedAt = $publishedAt;

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

    public function getIsPublic(): ?bool
    {
        return $this->isPublic;
    }

    public function setIsPublic(bool $isPublic): self
    {
        $this->isPublic = $isPublic;

        return $this;
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
}
