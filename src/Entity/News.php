<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use App\Controller\Event\RandomEventsListAction;
use App\Controller\News\UpdatePictureAction;
use App\Repository\NewsRepository;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Serializer\Annotation\Groups;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

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
        'get',
        'post',
        'randomEventsList' => [
            'method' => 'GET',
            'path' => '/randomEventsList',
            'controller' => RandomEventsListAction::class,
        ],

    ],
    itemOperations      : [
        'get', 'put', 'delete',
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
    normalizationContext: [
        'groups' => [
            'news:read'
        ]
    ])]
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
    #[Groups(['news:read'])]
    private string $name;


    
    /**
     * @var string    
     */
    #[ORM\Column(type: 'text')]
    #[Groups(['news:read'])]
    private string $description;


    /**
     * @var boolean     
     */
    #[ORM\Column(type: 'boolean')]
    #[Groups(['news:read'])]
    private bool $forAllUniversities;


    /**
     * @var string|null  
     */
    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Groups(['news:read'])]
    private ?string $university;


    /**
     * @var DateTimeImmutable
     */
    #[ORM\Column(type: 'datetime_immutable', nullable: false)]
    #[Groups(['news:read'])]
    private DateTimeImmutable $publishedAt;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Groups(['news:read'])]
    private ?string $image;


    /**
     * @Vich\UploadableField(mapping="media_object", fileNameProperty="image")
     */
    #[Groups(['news:updatePicture'])]
    public ?File $imageFile = null;

    #[ORM\Column(type: 'boolean')]
    #[Groups(['news:read'])]
    private bool $isPublic;

    #[ORM\ManyToOne(targetEntity: NewsCategory::class, inversedBy: 'news')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['news:read'])]
    private NewsCategory $category;


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
    #[Groups(['news:read'])]
    private string $chapo;


         

  
     
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

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

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

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
