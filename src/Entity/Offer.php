<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\RangeFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use App\Controller\Offer\ArchiveOfferAction;
use App\Controller\Offer\CreateOfferAction;
use App\Controller\Offer\DeleteOfferAction;
use App\Controller\Offer\MultipleDeleteOfferAction;
use App\Controller\Offer\MultipleValidateOfferAction;
use App\Controller\Offer\ReactivateExpiredOfferAction;
use App\Controller\Offer\RefuseOfferAction;
use App\Controller\Offer\SetFulfilledOfferAction;
use App\Controller\Offer\UpdateImageStockAction;
use App\Controller\Offer\UpdateLogoAction;
use App\Controller\Offer\UpdatePictureAction;
use App\Controller\Offer\ValidateOfferAction;
use App\Repository\OfferRepository;
use App\Validator\Constraints\Offer as Assert;
use DateTime;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Doctrine\ORM\Mapping\PreUpdate;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Serializer\Annotation\Groups;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as AssertVendor;


/**
 * Class Offer
 *
 * @package App\Entity
 * @ApiFilter(SearchFilter::class, properties={"title": "partial", "description": "partial", "city":"exact",
 *     "country":"exact", "domain":"exact", "typeOfContract":"exact", "offerCategory":"exact", "sector":"exact",
 *     "levelOfEducations":"exact" })
 * @ApiFilter(RangeFilter::class, properties={"minSalary","maxSalary"})
 * @ApiFilter(OrderFilter::class, properties={"datePosted" : "DESC"})
 */
#[ORM\Entity(repositoryClass: OfferRepository::class)]
#[ApiResource(
    collectionOperations: [
        'get'=> [
            'normalization_context' => [
                'groups' => [
                    'offer:read'
                ],
                'openapi_definition_name' => 'read collection'
            ]
        ],
        'create_offer' => [
            'method' => 'POST',
            'path' => '/offers/create',
            'controller' => CreateOfferAction::class,
            'input_formats' => [
                'multipart' => ['multipart/form-data'],
            ],
        ],
        'multiple_delete_offers' => [
            'method' => 'DELETE',
            'path' => '/offer/delete/multiple',
            'controller' => MultipleDeleteOfferAction::class,
            'openapi_context' => [
                'parameters' => [
                    [
                        'in' => 'query',
                        'name' => 'ids',
                        'description' => 'List of IDs to delete',
                        'required' => true,
                        'schema' => ['type' => 'array']
                    ]
                ],
                'requestBody' => [
                    'content' => [
                        'application/json' => []
                    ]
                ]
            ],
            'requirements' => [],
            'read' => false,
            'deserialize' => false,
            'validate' => false
        ],
        'multiple_validate_offers' => [
            'method' => 'POST',
            'path' => '/offer/validate/multiple',
            'controller' => MultipleValidateOfferAction::class,
            'openapi_context' => [
                'parameters' => [
                    [
                        'in' => 'query',
                        'name' => 'ids',
                        'description' => 'List of IDs to validate',
                        'required' => true,
                        'schema' => ['type' => 'array']
                    ]
                ],
                'requestBody' => [
                    'content' => [
                        'application/json' => []
                    ]
                ]
            ],
            'requirements' => [],
            'read' => false,
            'deserialize' => false,
            'validate' => false
        ]
    ],
    itemOperations: [
        'get' => [
            'normalization_context' => [
                'groups' => [
                    'offer:read',
                    'offer:read:item'
                ],
                'openapi_definition_name' => 'read item'
            ]
        ],
        'put',
        'validate_offer' => ['method' => 'POST',
            'path' => '/offers/{id}/validate',
            'security' => 'is_granted("ROLE_ADMIN")',
            'controller' => ValidateOfferAction::class,
        ],
        'delete_offer' => [
            'method' => 'DELETE',
            'path' => '/offers/{id}/',
            'controller' => DeleteOfferAction::class,
        ],
        'setFulfilled_offer' => [
            'method' => 'POST',
            'path' => '/offers/{id}/fulfill',
            'controller' => SetFulfilledOfferAction::class,
        ],
        'archive_offer' => [
            'method' => 'POST',
            'path' => '/offers/{id}/archive',
            'controller' => ArchiveOfferAction::class,
        ],
        'refuse_offer' => [
            'method' => 'POST',
            'path' => '/offers/{id}/refuse',
            'security' => 'is_granted("ROLE_ADMIN")',
            'controller' => RefuseOfferAction::class,
        ],
        'reactivate_offer' => [
            'method' => 'POST',
            'path' => '/offers/{id}/reactivate',
            'controller' => ReactivateExpiredOfferAction::class,
        ],
        'update_logo_offer' => [
            'method' => 'POST',
            'path' => '/offer/{id}/updateLogo',
            'openapi_context' => [
                'summary' => 'Use this endpoint to update only the logo of the offer. Use the PUT endpoint for all other updating'
            ],
            'controller' => UpdateLogoAction::class,
            'denormalization_context' => ['groups' => ['offer:updateLogo']],
            'input_formats' => [
                'multipart' => ['multipart/form-data'],
            ]
        ],
        'updatePicture' => [
            'method' => 'POST',
            'path' => '/offer/{id}/updatePicture',
            'openapi_context' => [
                'summary' => 'Use this endpoint to update only the picture of the offer, or /updateLogo by the logo. Use the PUT endpoint for all other updating'
            ],
            'controller' => UpdatePictureAction::class,
            'denormalization_context' => ['groups' => ['offer:updatePicture']],
            'input_formats' => [
                'multipart' => ['multipart/form-data'],
            ]
        ],
        'updateImageStock' => [
            'method' => 'POST',
            'path' => '/offer/{id}/updateImageStock',
            'openapi_context' => [
                'summary' => 'Use this endpoint to update only the picture of "banque d\'images"'
            ],
            'controller' => UpdateImageStockAction::class,
            'denormalization_context' => ['groups' => ['offer:updateImageStock']],
            'input_formats' => [
                'json' => ['application/json'],
            ]
        ],
    ],
    attributes: ["pagination_enabled" => false],
    denormalizationContext: [
        'groups' => [
            'offer:write',
            'offer:create'
        ]
    ],
    normalizationContext: [
        'groups' => [
        'offer:read',
        'openapi_definition_name' => 'read collection'
        ]
    ]
)]
#[HasLifecycleCallbacks]
/**
 * @Vich\Uploadable()
 */
class Offer implements ImageStockCompatibleInterface, UploadPictureCompatibleInterface
{

    /**
     * @var int|null
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['offer:read', 'offer:write'])]
    private ?int $id;

    /**
     * @var string
     */
    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['offer:read', 'offer:write'])]
    private string $title;

    /**
     * @var string|null
     */
    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Groups(['offer:read', 'offer:write'])]
    private ?string $description;

    /**
     * @var string|null
     */
    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Groups(['offer:read', 'offer:write'])]
    private ?string $city;

    /**
     * @var string|null
     */
    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Groups(['offer:read', 'offer:write'])]
    private ?string $country;

    /**
     * @var DateTimeInterface
     */
    #[ORM\Column(type: 'date')]
    #[Groups(['offer:read', 'offer:write'])]
    private DateTimeInterface $datePosted;


    #[ORM\Column(type: 'integer', nullable: true)]
    #[Groups(['offer:read', 'offer:write'])]
    private ?int $publishDuration;

    /**
     * @var Domain|null
     */
    #[ORM\ManyToOne(targetEntity: Domain::class, inversedBy: 'offers')]
    #[Groups(['offer:read', 'offer:write'])]
    private ?Domain $domain;


    /**
     * @var int|null
     */
    #[ORM\Column(type: 'integer', nullable: true)]
    #[Groups(['offer:read', 'offer:write'])]
    private ?int $minSalary;

    /**
     * @var int|null
     */
    #[ORM\Column(type: 'integer', nullable: true)]
    #[Groups(['offer:read', 'offer:write'])]
    private ?int $maxSalary;

    /**
     * @var TypeOfContract|null
     */
    #[ORM\ManyToOne(targetEntity: TypeOfContract::class, inversedBy: 'offers')]
    #[ORM\JoinColumn(nullable: true)]
    #[Groups(['offer:read', 'offer:write'])]
    private ?TypeOfContract $typeOfContract;

    /**
     * @var string|null
     */
    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Groups(['offer:read', 'offer:write'])]
    private ?string $companyName;

    /**
     * @var bool
     */
    #[ORM\Column(type: 'boolean')]
    #[Groups(['offer:read', 'offer:write'])]
    private bool $isDirect = false;

    /**
     * @var bool
     */
    #[ORM\Column(type: 'boolean')]
    #[Groups(['offer:read', 'offer:write'])]
    private bool $isPublic = false;

    /**
     * @var string|null
     */
    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Groups(['offer:read', 'offer:write'])]
    private ?string $postedBy;

    /**
     * @var bool
     */
    #[ORM\Column(type: 'boolean')]
    #[Groups(['offer:read', 'offer:write'])]
    private bool $postedByPartner = false;


    /**
     * @var User|null
     */
    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'offers')]
    #[ORM\JoinColumn(nullable: true)]
    #[Groups(['offer:read', 'offer:write'])]
    private ?User $createdByUser;


    /**
     * @var int|null
     */
    #[ORM\Column(type: 'integer', nullable: true)]
    #[Groups(['offer:read', 'offer:write'])]
    private ?int $views;


    /**
     * @var int|null
     */
    #[ORM\Column(type: 'integer', nullable: true)]
    #[Groups(['offer:read', 'offer:write'])]
    private ?int $numberOfCandidatures;


    /**
     * @var string|null
     */
    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Groups(['offer:read', 'offer:write'])]
    private ?string $experience;


    # Add a logo to an offer was optional but not detailed in the spec, so we'll fix the problem
    # if it's imperative

    /**
     * @var string|null
     */
    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Groups(['offer:read'])]
    private ?string $logoLink = null;

    /**
     * @Vich\UploadableField(mapping="media_object", fileNameProperty="logoLink")
     */
    #[Groups(['offer:updateLogo', 'offer:write'])]
    #[Assert\LogoFileRequirements]
    public ?File $logoFile = null;

    #[ApiProperty(iri: 'http://schema.org/imageStockId')]
    #[Groups(['offer:create'])]
    public ?string $imageStockId = null;

    #[ApiProperty(iri: 'http://schema.org/imageUrl')]
    #[Groups(['offer:read'])]
    public ?string $imageUrl = null;


    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    public ?string $imagePath = null;

    /**
     * @Vich\UploadableField(mapping="media_object", fileNameProperty="imagePath")
     */
    #[Groups(['offer:updatePicture', 'offer:write', 'offer:create'])]
    #[Assert\ImageFileRequirements]
    public ?File $imageFile = null;

    /**
     * @var Collection<int,Candidature>
     */
    #[ORM\OneToMany(mappedBy: 'offer', targetEntity: Candidature::class)]
    private Collection $candidatures;

    /**
     * A unique offer ID to identify offers across ReseauPro
     *
     * @var string
     */
    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['offer:read', 'offer:write'])]
    private string $offerId;

    /**
     * @var OfferStatus
     */
    #[ORM\ManyToOne(targetEntity: OfferStatus::class)]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['offer:read', 'offer:write'])]
    private OfferStatus $offerStatus;

    /**
     * @var DateTimeInterface
     */
    #[ORM\Column(type: 'date')]
    #[Groups(['offer:read', 'offer:write'])]
    private DateTimeInterface $dateModified;

    /**
     * @var OfferCategory|null
     */
    #[ORM\ManyToOne(targetEntity: OfferCategory::class, inversedBy: 'offers')]
    #[Groups(['offer:read', 'offer:write'])]
    private ?OfferCategory $offerCategory;

    /**
     * @var SectorOfOffer|null
     */
    #[ORM\ManyToOne(targetEntity: SectorOfOffer::class, inversedBy: 'offers')]
    #[Groups(['offer:read', 'offer:write'])]
    private ?SectorOfOffer $sector;

    /**
     * @var Collection<int, LevelOfEducation>
     */
    #[ORM\ManyToMany(targetEntity: LevelOfEducation::class, inversedBy: 'offers')]
    #[Groups(['offer:read', 'offer:write'])]
    private Collection $levelOfEducations;

    /**
     * @var string|null
     */
    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Groups(['offer:read', 'offer:write'])]
    private ?string $urlCompany;

    /**
     * @var string|null
     */
    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Groups(['offer:read', 'offer:write'])]
    private ?string $urlCandidature;

    /**
     * @var bool
     */
    #[ORM\Column(type: 'boolean')]
    #[Groups(['offer:read', 'offer:write'])]
    private bool $accessibleForDisabled = false;

    /**
     * @var Collection<int, OfferContact>
     */
    #[ORM\OneToMany(mappedBy: 'offer', targetEntity: OfferContact::class, cascade: ['persist'], orphanRemoval: true)]
    #[Groups(['offer:read', 'offer:write'])]
    private Collection $contacts;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?\DateTimeInterface $updatedAt;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    private ?\DateTimeImmutable $createdAt;


    public function __construct()
    {
        $this->candidatures = new ArrayCollection();
        $this->datePosted = new DateTime('now');
        $this->dateModified = new DateTime('now');
        $this->levelOfEducations = new ArrayCollection();
        $this->contacts = new ArrayCollection();
        $this->createdAt = new \DateTimeImmutable();
    }


    /**
     * @param PreUpdateEventArgs $eventArgs
     * @return void
     */
    #[PreUpdate]
    public function doStuffOnPreUpdate(PreUpdateEventArgs $eventArgs)
    {
        $this->dateModified = new DateTime('now');
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
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return $this
     */
    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string|null $description
     * @return $this
     */
    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getCity(): ?string
    {
        return $this->city;
    }

    /**
     * @param string|null $city
     * @return $this
     */
    public function setCity(?string $city): self
    {
        $this->city = $city;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getCountry(): ?string
    {
        return $this->country;
    }

    /**
     * @param string|null $country
     * @return $this
     */
    public function setCountry(?string $country): self
    {
        $this->country = $country;

        return $this;
    }

    /**
     * @return DateTimeInterface|null
     */
    public function getDatePosted(): ?DateTimeInterface
    {
        return $this->datePosted;
    }

    /**
     * @param DateTimeInterface $datePosted
     * @return $this
     */
    public function setDatePosted(DateTimeInterface $datePosted): self
    {
        $this->datePosted = $datePosted;

        if (!isset($this->dateModified)){
            $this->setDateModified(new DateTime('now'));
        }

        return $this;
    }

    /**
     * @return int|null
     */
    public function getPublishDuration(): ?int
    {
        return $this->publishDuration;
    }

    /**
     * @param int|null $publishDuration
     * @return $this
     */
    public function setPublishDuration(?int $publishDuration): self
    {
        $this->publishDuration = $publishDuration;

        return $this;
    }

    /**
     * @return Domain|null
     */
    public function getDomain(): ?Domain
    {
        return $this->domain;
    }

    /**
     * @param Domain|null $domain
     * @return $this
     */
    public function setDomain(?Domain $domain): self
    {
        $this->domain = $domain;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getMinSalary(): ?int
    {
        return $this->minSalary;
    }

    /**
     * @param int|null $minSalary
     * @return $this
     */
    public function setMinSalary(?int $minSalary): self
    {
        $this->minSalary = $minSalary;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getMaxSalary(): ?int
    {
        return $this->maxSalary;
    }

    /**
     * @param int|null $maxSalary
     * @return $this
     */
    public function setMaxSalary(?int $maxSalary): self
    {
        $this->maxSalary = $maxSalary;

        return $this;
    }

    /**
     * @return TypeOfContract|null
     */
    public function getTypeOfContract(): ?TypeOfContract
    {
        return $this->typeOfContract;
    }

    /**
     * @param TypeOfContract|null $typeOfContract
     * @return $this
     */
    public function setTypeOfContract(?TypeOfContract $typeOfContract): self
    {
        $this->typeOfContract = $typeOfContract;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getCompanyName(): ?string
    {
        return $this->companyName;
    }

    /**
     * @param string|null $companyName
     * @return $this
     */
    public function setCompanyName(?string $companyName): self
    {
        $this->companyName = $companyName;

        return $this;
    }

    /**
     * @return bool|null
     */
    public function getIsDirect(): ?bool
    {
        return $this->isDirect;
    }

    /**
     * @param bool $isDirect
     * @return $this
     */
    public function setIsDirect(bool $isDirect): self
    {
        $this->isDirect = $isDirect;

        return $this;
    }


    /**
     * @return bool|null
     */
    public function getIsPublic(): ?bool
    {
        return $this->isPublic;
    }

    /**
     * @param bool $isPublic
     * @return $this
     */
    public function setIsPublic(bool $isPublic): self
    {
        $this->isPublic = $isPublic;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getPostedBy(): ?string
    {
        return $this->postedBy;
    }

    /**
     * @param string|null $postedBy
     * @return $this
     */
    public function setPostedBy(?string $postedBy): self
    {
        $this->postedBy = $postedBy;

        return $this;
    }

    /**
     * @return bool|null
     */
    public function isPostedByPartner(): ?bool
    {
        return $this->postedByPartner;
    }

    /**
     * @param bool $postedByPartner
     * @return $this
     */
    public function setPostedByPartner(bool $postedByPartner): self
    {
        $this->postedByPartner = $postedByPartner;

        return $this;
    }

    /**
     * @return User|null
     */
    public function getCreatedByUser(): ?User
    {
        return $this->createdByUser;
    }

    /**
     * @param User|null $createdByUser
     * @return $this
     */
    public function setCreatedByUser(?User $createdByUser): self
    {
        $this->createdByUser = $createdByUser;

        return $this;
    }


    /**
     * @return int|null
     */
    public function getViews(): ?int
    {
        return $this->views;
    }

    /**
     * @param int|null $views
     * @return $this
     */
    public function setViews(?int $views): self
    {
        $this->views = $views;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getNumberOfCandidatures(): ?int
    {
        return $this->numberOfCandidatures;
    }

    /**
     * @param int|null $numberOfCandidatures
     * @return $this
     */
    public function setNumberOfCandidatures(?int $numberOfCandidatures): self
    {
        $this->numberOfCandidatures = $numberOfCandidatures;

        return $this;
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
    public function getLogoLink(): ?string
    {
        return $this->logoLink;
    }

    /**
     * @param string|null $logoLink
     * @return $this
     */
    public function setLogoLink(?string $logoLink): self
    {
        $this->logoLink = $logoLink;

        return $this;
    }

    /**
     * @return File|null
     */
    public function getLogoFile(): ?File
    {
        return $this->logoFile;
    }

    /**
     * @param File|null $logoFile
     */
    public function setLogoFile(?File $logoFile): void
    {
        $this->logoFile = $logoFile;
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

    public function getImagePath(): ?string
    {
        return $this->imagePath;
    }

    public function setImagePath(?string $imagePath): self
    {
        $this->imagePath = $imagePath;

        return $this;
    }


    /**
     * @return Collection<int, Candidature>
     */
    public function getCandidatures(): Collection
    {
        return $this->candidatures;
    }

    /**
     * @return string|null
     */
    public function getOfferId(): ?string
    {
        return $this->offerId;
    }

    /**
     * @param string $offerId
     * @return $this
     */
    public function setOfferId(string $offerId): self
    {
        $this->offerId = $offerId;

        return $this;
    }

    /**
     * @return OfferStatus|null
     */
    public function getOfferStatus(): ?OfferStatus
    {
        return $this->offerStatus;
    }

    /**
     * @param OfferStatus $offerStatus
     * @return $this
     */
    public function setOfferStatus(OfferStatus $offerStatus): self
    {
        $this->offerStatus = $offerStatus;

        return $this;
    }

    /**
     * @return DateTimeInterface|null
     */
    public function getDateModified(): ?DateTimeInterface
    {
        return $this->dateModified;
    }

    /**
     * @param DateTimeInterface $dateModified
     * @return $this
     */
    public function setDateModified(DateTimeInterface $dateModified): self
    {
        $this->dateModified = $dateModified;

        return $this;
    }

    /**
     * @return OfferCategory|null
     */
    public function getOfferCategory(): ?OfferCategory
    {
        return $this->offerCategory;
    }

    /**
     * @param OfferCategory|null $offerCategory
     * @return $this
     */
    public function setOfferCategory(?OfferCategory $offerCategory): self
    {
        $this->offerCategory = $offerCategory;

        return $this;
    }

    /**
     * @return SectorOfOffer|null
     */
    public function getSector(): ?SectorOfOffer
    {
        return $this->sector;
    }

    /**
     * @param SectorOfOffer|null $sector
     * @return $this
     */
    public function setSector(?SectorOfOffer $sector): self
    {
        $this->sector = $sector;

        return $this;
    }

    /**
     * @return Collection<int, LevelOfEducation>
     */
    public function getLevelOfEducations(): Collection
    {
        return $this->levelOfEducations;
    }

    /**
     * @param Collection<int,LevelOfEducation> $levelOfEducations
     * @return $this
     */
    public function setLevelOfEducations(Collection $levelOfEducations): self
    {
        $this->levelOfEducations = $levelOfEducations;

        return $this;
    }


    /**
     * @param LevelOfEducation $levelOfEducation
     * @return $this
     */
    public function addLevelOfEducation(LevelOfEducation $levelOfEducation): Offer
    {

        if (!$this->levelOfEducations->contains($levelOfEducation)){
            $this->levelOfEducations->add($levelOfEducation);
            $levelOfEducation->addOffer($this);
        }

        return $this;
    }

    /**
     * @param LevelOfEducation $levelOfEducation
     * @return $this
     */
    public function removeLevelOfEducation(LevelOfEducation $levelOfEducation): Offer
    {
        if ($this->levelOfEducations->contains($levelOfEducation)){
            $this->levelOfEducations->removeElement($levelOfEducation);
            $levelOfEducation->removeOffer($this);
        }

        return $this;
    }

    /**
     * @return string|null
     */
    public function getUrlCompany(): ?string
    {
        return $this->urlCompany;
    }

    /**
     * @param string|null $urlCompany
     * @return $this
     */
    public function setUrlCompany(?string $urlCompany): self
    {
        $this->urlCompany = $urlCompany;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getUrlCandidature(): ?string
    {
        return $this->urlCandidature;
    }

    /**
     * @param string|null $urlCandidature
     * @return $this
     */
    public function setUrlCandidature(?string $urlCandidature): self
    {
        $this->urlCandidature = $urlCandidature;

        return $this;
    }

    /**
     * @return bool|null
     */
    public function isAccessibleForDisabled(): ?bool
    {
        return $this->accessibleForDisabled;
    }

    /**
     * @param bool $accessibleForDisabled
     * @return $this
     */
    public function setAccessibleForDisabled(bool $accessibleForDisabled): self
    {
        $this->accessibleForDisabled = $accessibleForDisabled;

        return $this;
    }

    /**
     * @return Collection<int, OfferContact>
     */
    public function getContacts(): Collection
    {
        return $this->contacts;
    }

    /**
     * @param OfferContact $contact
     * @return $this
     */
    public function addContact(OfferContact $contact): self
    {
        if (!$this->contacts->contains($contact)) {
            $this->contacts[] = $contact;
            $contact->setOffer($this);
        }

        return $this;
    }

    /**
     * @param OfferContact $contact
     * @return $this
     */
    public function removeContact(OfferContact $contact): self
    {
        if ($this->contacts->removeElement($contact)) {
            // set the owning side to null (unless already changed)
            if ($contact->getOffer() === $this) {
                $contact->setOffer(null);
            }
        }

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

    /**
     * @return string|null
     */
    public function getImageStockId(): ?string
    {
        return $this->imageStockId;
    }

    /**
     * @param string|null $imageStockId
     */
    public function setImageStockId(?string $imageStockId): void
    {
        $this->imageStockId = $imageStockId;
    }
}
