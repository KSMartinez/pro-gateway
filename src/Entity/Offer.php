<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use App\Controller\Offer\ValidateOfferAction;
use App\Repository\OfferRepository;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Offer
 * @package App\Entity
 * @ApiFilter(SearchFilter::class, properties={"title": "partial", "description": "partial", "city":"exact", "country":"exact", "domain":"exact"})
 * @ApiFilter(OrderFilter::class, properties={"datePosted" : "DESC"})
 */
#[ORM\Entity(repositoryClass: OfferRepository::class)]
#[ApiResource(itemOperations: [
    'get','put','delete', 'patch',
    'validate_offer' => ['method' => 'POST',
        'path' => '/offers/{id}/validate',
        'controller' => ValidateOfferAction::class,
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
     *
     */
    public function __construct()
    {
        $this->candidatures = new ArrayCollection();
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
