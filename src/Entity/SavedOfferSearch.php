<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\SavedOfferSearchRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 *
 */
#[ORM\Entity(repositoryClass: SavedOfferSearchRepository::class)]
#[ApiResource]
class SavedOfferSearch
{
    /**
     * @var int|null
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id;
    /**
     * @var User
     */
    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'savedOfferSearches')]
    #[ORM\JoinColumn(nullable: false)]
    private User $user;
    /**
     * @var string
     */
    #[ORM\Column(type: 'string', length: 255)]
    private string $url;
    /**
     * @var string
     */
    #[ORM\Column(type: 'string', length: 255)]
    private string $nameOfSearch;
    /**
     * @var string|null
     */
    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $title;
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
     * @var Domain|null
     */
    #[ORM\ManyToOne(targetEntity: Domain::class)]
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
     * @var TypeOfContract|null
     */
    #[ORM\ManyToOne(targetEntity: TypeOfContract::class)]
    private ?TypeOfContract $typeOfContract;
    /**
     * @var string|null
     */
    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $companyName;
    #[ORM\Column(type: 'boolean')]
    private bool $isActive = true;

    /**
     * @var DateTimeInterface|null
     */
    #[ORM\Column(type: 'date', nullable: true)]
    private ?DateTimeInterface $lastSearch;


    public function __construct()
    {
        $this->lastSearch = new DateTime('now');
    }

    /**
     * @return string|null
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @param string|null $title
     */
    public function setTitle(?string $title): void
    {
        $this->title = $title;
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
     */
    public function setDescription(?string $description): void
    {
        $this->description = $description;
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
     */
    public function setCity(?string $city): void
    {
        $this->city = $city;
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
     */
    public function setCountry(?string $country): void
    {
        $this->country = $country;
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
     */
    public function setDomain(?Domain $domain): void
    {
        $this->domain = $domain;
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
     */
    public function setMinSalary(?int $minSalary): void
    {
        $this->minSalary = $minSalary;
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
     */
    public function setMaxSalary(?int $maxSalary): void
    {
        $this->maxSalary = $maxSalary;
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
     */
    public function setTypeOfContract(?TypeOfContract $typeOfContract): void
    {
        $this->typeOfContract = $typeOfContract;
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
     */
    public function setCompanyName(?string $companyName): void
    {
        $this->companyName = $companyName;
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
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
     * @return string|null
     */
    public function getUrl(): ?string
    {
        return $this->url;
    }

    /**
     * @param string $url
     * @return $this
     */
    public function setUrl(string $url): self
    {
        $this->url = $url;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getNameOfSearch(): ?string
    {
        return $this->nameOfSearch;
    }

    /**
     * @param string $nameOfSearch
     * @return $this
     */
    public function setNameOfSearch(string $nameOfSearch): self
    {
        $this->nameOfSearch = $nameOfSearch;

        return $this;
    }

    public function getIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): self
    {
        $this->isActive = $isActive;

        return $this;
    }

    public function getLastSearch(): ?DateTimeInterface
    {
        return $this->lastSearch;
    }

    public function setLastSearch(DateTimeInterface $lastSearch): self
    {
        $this->lastSearch = $lastSearch;

        return $this;
    }
}
