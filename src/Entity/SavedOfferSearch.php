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
#[ApiResource(
    collectionOperations: ['get', 'post'],
    itemOperations: ['get', 'put', 'delete']
)]
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
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     * @return SavedOfferSearch
     */
    public function setId(?int $id): SavedOfferSearch
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @param User $user
     * @return SavedOfferSearch
     */
    public function setUser(User $user): SavedOfferSearch
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @param string $url
     * @return SavedOfferSearch
     */
    public function setUrl(string $url): SavedOfferSearch
    {
        $this->url = $url;
        return $this;
    }

    /**
     * @return string
     */
    public function getNameOfSearch(): string
    {
        return $this->nameOfSearch;
    }

    /**
     * @param string $nameOfSearch
     * @return SavedOfferSearch
     */
    public function setNameOfSearch(string $nameOfSearch): SavedOfferSearch
    {
        $this->nameOfSearch = $nameOfSearch;
        return $this;
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
     * @return SavedOfferSearch
     */
    public function setTitle(?string $title): SavedOfferSearch
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
     * @return SavedOfferSearch
     */
    public function setDescription(?string $description): SavedOfferSearch
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
     * @return SavedOfferSearch
     */
    public function setCity(?string $city): SavedOfferSearch
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
     * @return SavedOfferSearch
     */
    public function setCountry(?string $country): SavedOfferSearch
    {
        $this->country = $country;
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
     * @return SavedOfferSearch
     */
    public function setDomain(?Domain $domain): SavedOfferSearch
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
     * @return SavedOfferSearch
     */
    public function setMinSalary(?int $minSalary): SavedOfferSearch
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
     * @return SavedOfferSearch
     */
    public function setMaxSalary(?int $maxSalary): SavedOfferSearch
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
     * @return SavedOfferSearch
     */
    public function setTypeOfContract(?TypeOfContract $typeOfContract): SavedOfferSearch
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
     * @return SavedOfferSearch
     */
    public function setCompanyName(?string $companyName): SavedOfferSearch
    {
        $this->companyName = $companyName;
        return $this;
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->isActive;
    }

    /**
     * @param bool $isActive
     * @return SavedOfferSearch
     */
    public function setIsActive(bool $isActive): SavedOfferSearch
    {
        $this->isActive = $isActive;
        return $this;
    }

    /**
     * @return DateTime|DateTimeInterface|null
     */
    public function getLastSearch(): DateTime|DateTimeInterface|null
    {
        return $this->lastSearch;
    }

    /**
     * @param DateTime|DateTimeInterface|null $lastSearch
     * @return SavedOfferSearch
     */
    public function setLastSearch(DateTime|DateTimeInterface|null $lastSearch): SavedOfferSearch
    {
        $this->lastSearch = $lastSearch;
        return $this;
    }


}
