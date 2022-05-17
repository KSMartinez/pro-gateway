<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use App\Controller\Offer\CreateOfferWithNotificationAction;
use App\Controller\Offer\OfferResponseAction;
use App\Controller\Offer\UpdateOfferAction;
use App\Controller\Offer\UpdateIsExpiredOfferAction;
use App\Controller\Offer\UpdateLogoAction;
use App\Controller\Offer\ValidateOfferAction;
use App\Repository\OfferRepository;
use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Serializer\Annotation\Groups;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * Class Offer
 *
 * @package App\Entity
 * @ApiFilter(SearchFilter::class, properties={"title": "partial", "description": "partial", "city":"exact",
 *     "country":"exact", "domain":"exact"})
 * @ApiFilter(OrderFilter::class, properties={"datePosted" : "DESC"})
 */
#[ORM\Entity(repositoryClass: OfferRepository::class)]
#[ApiResource(
    collectionOperations: [
        'createOfferWithNotification' => [
            'method' => 'POST',
            'path' => '/offers/createOfferWithNotification',
            'controller' => CreateOfferWithNotificationAction::class,
        ],
    ],
    itemOperations: [
        'get', 'put', 'delete', 'patch',
        'validate_offer' => ['method' => 'POST',
            'path' => '/offers/{id}/validate',
            'security' => 'is_granted("ROLE_ADMIN")',
            'controller' => ValidateOfferAction::class,
        ],
        'update_offer' => [
            'method' => 'PUT',
            'path' => '/offers/{id}/',
            'controller' => UpdateOfferAction::class,
        ],
        'update_IsExpired' => [
            'method' => 'PUT',
            'path' => '/offers/{id}/updateIsExpired',
            'controller' => UpdateIsExpiredOfferAction::class,
        ],
        'offer_Response' => [
            'method' => 'POST',
            'path' => '/offers/{id}/offerResponse',
            'controller' => OfferResponseAction::class,
        ],

        'update_logo_offer' => [
            'method' => 'POST',
            'path' => '/offer/{id}/updateLogo',
            'openapi_context' => [
                'summary' => 'Use this endpoint to update only the logo of the offer. Use the PUT endpoint for all other updating',
                'description' => "# Pop a great rabbit picture by color!\n\n![A great rabbit]"
            ],
            'controller' => UpdateLogoAction::class,
            'denormalization_context' => ['groups' => ['offer:updateLogo']],
            'input_formats' => [
                'multipart' => ['multipart/form-data'],
            ]

        ],



    ]

)]
class Offer
{

    /**
     * @var int|null
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id;

    /**
     * @var string
     */
    #[ORM\Column(type: 'string', length: 255)]
    private string $title;

    /**
     * @var string|null
     */
    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $description;

    /**
     * @var string|null
     */
    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $city;

    /**
     * @var string|null
     */
    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $country;

    /**
     * @var DateTimeInterface
     */
    #[ORM\Column(type: 'date')]
    private DateTimeInterface $datePosted;

    /**
     * @var int|null
     */
    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $publishDuration;

    /**
     * @var Domain|null
     */
    #[ORM\ManyToOne(targetEntity: Domain::class, inversedBy: 'offers')]
    private ?Domain $domain;

     /**
     * @var string|null
     */
    private ?string $offerResponse;


    /**
     * @var int|null
     */
    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $minSalary;

    /**
     * @var int|null
     */
    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $maxSalary;

    /**
     * @var TypeOfContract
     */
    #[ORM\ManyToOne(targetEntity: TypeOfContract::class, inversedBy: 'offers')]
    #[ORM\JoinColumn(nullable: false)]
    private TypeOfContract $typeOfContract;

    /**
     * @var string|null
     */
    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $companyName;

    /**
     * @var bool
     */
    #[ORM\Column(type: 'boolean')]
    private bool $isDirect;

    /**
     * @var bool
     */
    #[ORM\Column(type: 'boolean')]
    private bool $isValid;

    /**
     * @var bool
     */
    #[ORM\Column(type: 'boolean')]
    private bool $isPublic;

    /**
     * @var string|null
     */
    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $postedBy;

    /**
     * @var bool
     */
    #[ORM\Column(type: 'boolean')]
    private bool $isOfPartner;


    /**
     * @var bool|null
     */
    #[ORM\Column(type: 'boolean', nullable: true)]
    private ?bool $validationInPending;


    /**
     * @var User
     */
    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'offers')]
    #[ORM\JoinColumn(nullable: false)]
    private User $user;


    /**
     * @var bool|null
     */
    #[ORM\Column(type: 'boolean', nullable: true)]
    private ?bool $validationCompleted;


    /**
     * @var bool|null
     */
    #[ORM\Column(type: 'boolean', nullable: true)]
    private ?bool $isArchived;


    /**
     * @var bool|null
     */
    #[ORM\Column(type: 'boolean', nullable: true)]
    private ?bool $isProvided;


    /**
     * @var bool|null
     */
    #[ORM\Column(type: 'boolean', nullable: true)]
    private ?bool $isRejected;


    /**
     * @var int|null
     */
    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $views;


    /**
     * @var int|null
     */
    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $numberOfApplications;


    /**
     * @var DateTimeImmutable|null
     */
    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    private ?DateTimeImmutable $publishedAt;


    /**
     * @var bool|null
     */
    #[ORM\Column(type: 'boolean', nullable: true)]
    private ?bool $isExpired;


    /**
     * @var bool|null
     */
    #[ORM\Column(type: 'boolean', nullable: true)]
    private ?bool $inReactivation;


    /**
     * @var DateTimeImmutable|null
     */
    #[ORM\Column(type: 'date_immutable', nullable: true)]
    private ?DateTimeImmutable $dateReactivated;

    /**
     * @var Collection<int, Application>
     */
    #[ORM\OneToMany(mappedBy: 'offer', targetEntity: Application::class)]
    private Collection|ArrayCollection $applications;


    /**
     * @var string|null
     */
    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $experience;


    # Add a logo to an offer was optional but not detailled in the spec, so we'll fix the problem
    # if it's imperative

    /**
     * @var string|null
     */
    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $logoLink = null;


    /**
     * @Vich\UploadableField(mapping="media_object", fileNameProperty="logoLink")
     */
    #[Groups(['offer:updateLogo'])]
    public ?File $logoFile = null;


    public function __construct()
    {
        $this->applications = new ArrayCollection();
        $this->candidatures = new ArrayCollection();

    }


    /**
     * @var Collection<int,Candidature>
     */
    #[ORM\OneToMany(mappedBy: 'offer', targetEntity: Candidature::class)]
    private Collection $candidatures;

    /**
     * @var string
     */
    #[ORM\Column(type: 'string', length: 255)]
    private string $offerId;


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
     * @param TypeOfContract $typeOfContract
     * @return $this
     */
    public function setTypeOfContract(TypeOfContract $typeOfContract): self
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
    public function getIsValid(): ?bool
    {
        return $this->isValid;
    }

    /**
     * @param bool $isValid
     * @return $this
     */
    public function setIsValid(bool $isValid): self
    {
        $this->isValid = $isValid;

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
    public function getIsOfPartner(): ?bool
    {
        return $this->isOfPartner;
    }

    /**
     * @param bool $isOfPartner
     * @return $this
     */
    public function setIsOfPartner(bool $isOfPartner): self
    {
        $this->isOfPartner = $isOfPartner;

        return $this;
    }

    public function getValidationInPending(): ?bool
    {
        return $this->validationInPending;
    }

    public function setValidationInPending(?bool $validationInPending): self
    {
        $this->validationInPending = $validationInPending;

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

    public function getValidationCompleted(): ?bool
    {
        return $this->validationCompleted;
    }

    public function setValidationCompleted(?bool $validationCompleted): self
    {
        $this->validationCompleted = $validationCompleted;

        return $this;
    }

    public function getIsArchived(): ?bool
    {
        return $this->isArchived;
    }

    public function setIsArchived(?bool $isArchived): self
    {
        $this->isArchived = $isArchived;

        return $this;
    }

    public function getIsProvided(): ?bool
    {
        return $this->isProvided;
    }

    public function setIsProvided(?bool $isProvided): self
    {
        $this->isProvided = $isProvided;

        return $this;
    }

    public function getIsRejected(): ?bool
    {
        return $this->isRejected;
    }

    public function setIsRejected(?bool $isRejected): self
    {
        $this->isRejected = $isRejected;

        return $this;
    }

    public function getViews(): ?int
    {
        return $this->views;
    }

    public function setViews(?int $views): self
    {
        $this->views = $views;

        return $this;
    }

    public function getNumberOfApplications(): ?int
    {
        return $this->numberOfApplications;
    }

    public function setNumberOfApplications(?int $numberOfApplications): self
    {
        $this->numberOfApplications = $numberOfApplications;

        return $this;
    }


    public function getPublishedAt(): ?\DateTimeImmutable
    {
        return $this->publishedAt;
    }

    public function setPublishedAt(?\DateTimeImmutable $publishedAt): self
    {
        $this->publishedAt = $publishedAt;

        return $this;
    }

    public function getIsExpired(): ?bool
    {
        return $this->isExpired;
    }

    public function setIsExpired(?bool $isExpired): self
    {
        $this->isExpired = $isExpired;

        return $this;
    }

    public function getInReactivation(): ?bool
    {
        return $this->inReactivation;
    }

    public function setInReactivation(?bool $inReactivation): self
    {
        $this->inReactivation = $inReactivation;

        return $this;
    }

    public function getDateReactivated(): ?\DateTimeImmutable
    {
        return $this->dateReactivated;
    }

    public function setDateReactivated(?\DateTimeImmutable $dateReactivated): self
    {
        $this->dateReactivated = $dateReactivated;

        return $this;
    }


    /**
     * @return string|null
     */
    public function getOfferResponse(): ?string
    {
        return $this->offerResponse;
    }

    /**
     * @param string|null $offerResponse
     * @return $this
     */
    public function setOfferResponse(?string $offerResponse): self
    {
        $this->offerResponse = $offerResponse;

        return $this;
    }

    /**
     * @return Collection<int, Application>
     */
    public function getApplications(): Collection
    {
        return $this->applications;
    }

    public function addApplication(Application $application): self
    {
        if (!$this->applications->contains($application)) {
            $this->applications[] = $application;
            $application->setOffer($this);
        }

        return $this;
    }

    public function removeApplication(Application $application): self
    {
        if ($this->applications->removeElement($application)) {
            // set the owning side to null (unless already changed)
            if ($application->getOffer() === $this) {
                $application->setOffer($this);
            }
        }

        return $this;
    }

    public function getExperience(): ?string
    {
        return $this->experience;
    }

    public function setExperience(?string $experience): self
    {
        $this->experience = $experience;

        return $this;
    }


    public function getLogoLink(): ?string
    {
        return $this->logoLink;
    }

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


}
