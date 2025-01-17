<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\CandidatureRepository;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use phpDocumentor\Reflection\Types\This;

/**
 * todo add unique user and offer constraint
 */
#[ORM\Entity(repositoryClass: CandidatureRepository::class)]
#[ApiResource(
    collectionOperations: [
        'post', 'get'
    ],
    itemOperations      : [
        'put', 'get', 'delete'
    ]
)]
class Candidature
{

    /**
     * @var int|null
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    /**
     * @var User
     */
    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'candidatures')]
    #[ORM\JoinColumn(nullable: false)]
    private User $user;

    /**
     * @var DateTimeInterface
     */
    #[ORM\Column(type: 'date')]
    private DateTimeInterface $dateOfCandidature;

    /**
     * @var Offer
     */
    #[ORM\ManyToOne(targetEntity: Offer::class, inversedBy: 'candidatures')]
    #[ORM\JoinColumn(nullable: false)]
    private Offer $offer;

    /**
     * @var string|null
     */
    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $message;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $extraCVFilePath;

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
     * @return DateTimeInterface|null
     */
    public function getDateOfCandidature(): ?DateTimeInterface
    {
        return $this->dateOfCandidature;
    }

    /**
     * @param DateTimeInterface $dateOfCandidature
     * @return $this
     */
    public function setDateOfCandidature(DateTimeInterface $dateOfCandidature): self
    {
        $this->dateOfCandidature = $dateOfCandidature;

        return $this;
    }

    /**
     * @return Offer|null
     */
    public function getOffer(): ?Offer
    {
        return $this->offer;
    }

    /**
     * @param Offer $offer
     * @return $this
     */
    public function setOffer(Offer $offer): self
    {
        $this->offer = $offer;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getMessage(): ?string
    {
        return $this->message;
    }

    /**
     * @param string|null $message
     * @return $this
     */
    public function setMessage(?string $message): self
    {
        $this->message = $message;

        return $this;
    }

    public function getExtraCVFilePath(): ?string
    {
        return $this->extraCVFilePath;
    }

    public function setExtraCVFilePath(?string $extraCVFilePath): self
    {
        $this->extraCVFilePath = $extraCVFilePath;

        return $this;
    }


    /**
     * @return array<mixed>
     */
    public function toArray(): array
    {

        return [
            'id' => $this->getId(),
            'dateOfCandidature' => $this->getDateOfCandidature()?->format('Y-m-d H:i:s.u'),
            'message' => $this->getMessage(),
            'user' => ($this->getUser()
                              ?->getId()),
            'offerId' => ($this->getOffer()?->getOfferId()),
        ];
    }


}
