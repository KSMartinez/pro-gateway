<?php

namespace App\Entity;

use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\NewsRepository;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Controller\News\UpdatePictureAction;
use Symfony\Component\HttpFoundation\File\File;
use App\Controller\Event\RandomEventsListAction;
use Symfony\Component\Serializer\Annotation\Groups;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;

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
#[ApiResource(collectionOperations: [
    'get',
    'post',
    'randomEventsList' => [
        'method' => 'GET',
        'path' => '/randomEventsList',
        'controller' => RandomEventsListAction::class,
    ],

], itemOperations: [
    'updatePicture' => [
        'method' => 'POST',
        'path' => '/news/{id}/updatePicture',
        'openapi_context' => [
            'summary'     => 'Use this endpoint to update only the picture of the news. Use the PUT endpoint for all other updating'
        ],
        'controller' => UpdatePictureAction::class,
        'denormalization_context' => ['groups' => ['news:updatePicture']],
        'input_formats' => [
            'multipart' => ['multipart/form-data'],
        ]

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
    private ?int $id = null;


    /**
     * @var string    
     */
    #[ORM\Column(type: 'string', length: 255)]
    private string $name;


    /**
     * @var string    
     */
    #[ORM\Column(type: 'text')]
    private string $description;


    /**
     * @var boolean     
     */
    #[ORM\Column(type: 'boolean')]
    private bool $forAllUniversities;


    /**
     * @var string|null  
     */
    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $university;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $category;

    /**
     * @var DateTimeImmutable
     */
    #[ORM\Column(type: 'datetime_immutable', nullable: false)]
    private DateTimeImmutable $publishedAt;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $image;


    /**
     * @Vich\UploadableField(mapping="media_object", fileNameProperty="image")
     */
    #[Groups(['news:updatePicture'])]
    public ?File $imageFile = null;

    #[ORM\Column(type: 'boolean')]
    private bool $isPublic;

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

    public function getCategory(): ?string
    {
        return $this->category;
    }

    public function setCategory(?string $category): self
    {
        $this->category = $category;

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
}
