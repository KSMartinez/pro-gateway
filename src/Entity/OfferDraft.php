<?php

namespace App\Entity;

use App\Entity\Domain;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\OfferDraftRepository;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Serializer\Annotation\Groups;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use App\Validator\Constraints\Offer as Assert;

/**
 * Class OfferDraft
 *
 * @package App\Entity
 */
#[ORM\Entity(repositoryClass: OfferDraftRepository::class)]
#[ApiResource(    itemOperations        : [
        'get', 'put', 'patch', 'delete']
        // 'delete_offer' => [
        //     'method' => 'DELETE',
        //     'path' => '/offers/{id}/',
        //     'controller' => DeleteOfferAction::class,
        // ],
        // 'update_logo_offer' => [
        //     'method' => 'POST',
        //     'path' => '/offer/{id}/updateLogo',
        //     'openapi_context' => [
        //         'summary' => 'Use this endpoint to update only the logo of the offer. Use the PUT endpoint for all other updating'
        //     ],
        //     'controller' => UpdateLogoAction::class,
        //     'denormalization_context' => ['groups' => ['offer:updateLogo']],
        //     'input_formats' => [
        //         'multipart' => ['multipart/form-data'],
        //     ]

        // ],
        // 'updatePicture' => [
        //     'method' => 'POST',
        //     'path' => '/offer/{id}/updatePicture',
        //     'openapi_context' => [
        //         'summary' => 'Use this endpoint to update only the picture of the offer, or /updateLogo by the logo. Use the PUT endpoint for all other updating'
        //     ],
        //     'controller' => UpdatePictureAction::class,
        //     'denormalization_context' => ['groups' => ['offer:updatePicture']],
        //     'input_formats' => [
        //         'multipart' => ['multipart/form-data'],
        //     ]

        // ]
        )]
class OfferDraft
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
     * @var string|null
     */
    #[ORM\Column(type: 'string', length: 255, nullable:true)]
    #[Groups(['offer:read', 'offer:write'])]
    private ?string $title;

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
     * @var DateTimeInterface|null
     */
    #[ORM\Column(type: 'date', nullable:true)]
    #[Groups(['offer:read', 'offer:write'])]
    private ?DateTimeInterface $datePosted;

    /**
     * @var int|null
     */
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
    #[ORM\ManyToOne(targetEntity: TypeOfContract::class, inversedBy: 'offerDrafts')]
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
     * @var bool|null
     */
    #[ORM\Column(type: 'boolean', nullable:true)]
    #[Groups(['offer:read', 'offer:write'])]
    private ?bool $isDirect = false;

    /**
     * @var bool|null
     */
    #[ORM\Column(type: 'boolean', nullable:true)]
    #[Groups(['offer:read', 'offer:write'])]
    private ?bool $isPublic = false;

    /**
     * @var string|null
     */
    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Groups(['offer:read', 'offer:write'])]
    private ?string $postedBy;

    /**
     * @var bool|null
     */
    #[ORM\Column(type: 'boolean', nullable:true)]
    #[Groups(['offer:read', 'offer:write'])]
    private ?bool $isOfPartner = false;

    /**
     * @var User|null
     */
    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'offerDrafts')]
    #[ORM\JoinColumn(nullable:true)]
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
    #[Assert\ImageFileRequirements]
    public ?File $logoFile = null;

    /**
     * @var string|null
     */
    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Groups(['offer:read'])]
    private ?string $image = null;

    /**
     * @Vich\UploadableField(mapping="media_object", fileNameProperty="image")
     */
    #[Groups(['offer:updatePicture', 'offer:write'])]
    #[Assert\ImageFileRequirements]
    public ?File $imageFile = null;

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
    #[ORM\ManyToOne(targetEntity: OfferStatus::class, inversedBy: 'offerDrafts')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['offer:read', 'offer:write'])]
    private OfferStatus $offerStatus;

    /**
     * @var DateTimeInterface|null
     */
    #[ORM\Column(type: 'datetime', nullable: true)]
    #[Groups(['offer:read', 'offer:write'])]
    private ?DateTimeInterface $dateModified;

    /**
     * @var OfferCategory|null
     */
    #[ORM\ManyToOne(targetEntity: OfferCategory::class, inversedBy: 'offerDrafts')]
    #[Groups(['offer:read', "offer:write"])]
    private ?OfferCategory $offerCategory;

    /**
     * @var SectorOfOffer|null
     */
    #[ORM\ManyToOne(targetEntity: SectorOfOffer::class, inversedBy: 'offerDrafts')]
    #[Groups(['offer:read', 'offer:write'])]
    private ?SectorOfOffer $sector;

    /**
     * @var Collection<int, LevelOfEducation>
     */
    #[ORM\ManyToMany(targetEntity: LevelOfEducation::class, inversedBy: 'offerDrafts')]
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
     * @var bool|null
     */
    #[ORM\Column(type: 'boolean', nullable:true)]
    #[Groups(['offer:read', 'offer:write'])]
    private ?bool $isAccessibleForDisabled;

    /**
     * @var Collection<int, OfferContact>
     */
    #[ORM\OneToMany(mappedBy: 'offerDraft', targetEntity: OfferContact::class, cascade:['persist'], orphanRemoval:true)]
    private $contacts;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    private ?\DateTimeImmutable $updateAt;

    public function __construct()
    {
        $this->levelOfEducations = new ArrayCollection();
        $this->contacts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(?string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(?string $country): self
    {
        $this->country = $country;

        return $this;
    }

    public function getDatePosted(): ?\DateTimeInterface
    {
        return $this->datePosted;
    }

    public function setDatePosted(?\DateTimeInterface $datePosted): self
    {
        $this->datePosted = $datePosted;

        return $this;
    }

    public function getPublishDuration(): ?int
    {
        return $this->publishDuration;
    }

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

    public function getMinSalary(): ?int
    {
        return $this->minSalary;
    }

    public function setMinSalary(?int $minSalary): self
    {
        $this->minSalary = $minSalary;

        return $this;
    }

    public function getMaxSalary(): ?int
    {
        return $this->maxSalary;
    }

    public function setMaxSalary(?int $maxSalary): self
    {
        $this->maxSalary = $maxSalary;

        return $this;
    }

    public function getCompanyName(): ?string
    {
        return $this->companyName;
    }

    public function setCompanyName(?string $companyName): self
    {
        $this->companyName = $companyName;

        return $this;
    }

    public function getIsDirect(): ?bool
    {
        return $this->isDirect;
    }

    public function setIsDirect(?bool $isDirect): self
    {
        $this->isDirect = $isDirect;

        return $this;
    }

    public function getIsPublic(): ?bool
    {
        return $this->isPublic;
    }

    public function setIsPublic(?bool $isPublic): self
    {
        $this->isPublic = $isPublic;

        return $this;
    }

    public function getPostedBy(): ?string
    {
        return $this->postedBy;
    }

    public function setPostedBy(?string $postedBy): self
    {
        $this->postedBy = $postedBy;

        return $this;
    }

    public function getIsOfPartner(): ?bool
    {
        return $this->isOfPartner;
    }

    public function setIsOfPartner(?bool $isOfPartner): self
    {
        $this->isOfPartner = $isOfPartner;

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

    public function getNumberOfCandidatures(): ?int
    {
        return $this->numberOfCandidatures;
    }

    public function setNumberOfCandidatures(?int $numberOfCandidatures): self
    {
        $this->numberOfCandidatures = $numberOfCandidatures;

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

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getDateModified(): ?\DateTimeInterface
    {
        return $this->dateModified;
    }

    public function setDateModified(?\DateTimeInterface $dateModified): self
    {
        $this->dateModified = $dateModified;

        return $this;
    }

    public function getUrlCompany(): ?string
    {
        return $this->urlCompany;
    }

    public function setUrlCompany(?string $urlCompany): self
    {
        $this->urlCompany = $urlCompany;

        return $this;
    }

    public function getUrlCandidature(): ?string
    {
        return $this->urlCandidature;
    }

    public function setUrlCandidature(?string $urlCandidature): self
    {
        $this->urlCandidature = $urlCandidature;

        return $this;
    }

    public function getIsAccessibleForDisabled(): ?bool
    {
        return $this->isAccessibleForDisabled;
    }

    public function setIsAccessibleForDisabled(bool $isAccessibleForDisabled): self
    {
        $this->isAccessibleForDisabled = $isAccessibleForDisabled;

        return $this;
    }

    public function getUpdateAt(): ?\DateTimeImmutable
    {
        return $this->updateAt;
    }

    public function setUpdateAt(?\DateTimeImmutable $updateAt): self
    {
        $this->updateAt = $updateAt;

        return $this;
    }

    public function getTypeOfContract(): ?TypeOfContract
    {
        return $this->typeOfContract;
    }

    public function setTypeOfContract(?TypeOfContract $typeOfContract): self
    {
        $this->typeOfContract = $typeOfContract;

        return $this;
    }

    public function getCreatedByUser(): ?User
    {
        return $this->createdByUser;
    }

    public function setCreatedByUser(?User $createdByUser): self
    {
        $this->createdByUser = $createdByUser;

        return $this;
    }

    public function getOfferStatus(): OfferStatus
    {
        return $this->offerStatus;
    }

    public function setOfferStatus(OfferStatus $offerStatus): self
    {
        $this->offerStatus = $offerStatus;

        return $this;
    }

    public function getOfferCategory(): ?OfferCategory
    {
        return $this->offerCategory;
    }

    public function setOfferCategory(?OfferCategory $offerCategory): self
    {
        $this->offerCategory = $offerCategory;

        return $this;
    }

    public function getSector(): ?SectorOfOffer
    {
        return $this->sector;
    }

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

    public function addLevelOfEducation(LevelOfEducation $levelOfEducation): self
    {
        if (!$this->levelOfEducations->contains($levelOfEducation)) {
            $this->levelOfEducations[] = $levelOfEducation;
        }

        return $this;
    }

    public function removeLevelOfEducation(LevelOfEducation $levelOfEducation): self
    {
        $this->levelOfEducations->removeElement($levelOfEducation);

        return $this;
    }

    /**
     * @return Collection<int, OfferContact>
     */
    public function getContacts(): Collection
    {
        return $this->contacts;
    }

    public function addContact(OfferContact $contact): self
    {
        if (!$this->contacts->contains($contact)) {
            $this->contacts[] = $contact;
            $contact->setOfferDraft($this);
        }

        return $this;
    }

    public function removeContact(OfferContact $contact): self
    {
        if ($this->contacts->removeElement($contact)) {
            // set the owning side to null (unless already changed)
            if ($contact->getOfferDraft() === $this) {
                $contact->setOfferDraft(null);
            }
        }

        return $this;
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
