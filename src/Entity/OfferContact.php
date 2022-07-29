<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\OfferContactRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: OfferContactRepository::class)]
#[ApiResource]
class OfferContact
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['offer:read'])]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Groups(['offer:read', 'offer:write'])]
    private ?string $email;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Groups(['offer:read', 'offer:write'])]
    private ?string $phone;

    #[ORM\ManyToOne(targetEntity: Offer::class, inversedBy: 'contacts')]
    #[ORM\JoinColumn(nullable: true)]
    private ?Offer $offer;

    /**
     * @var OfferDraft|null
     */
    #[ORM\ManyToOne(targetEntity: OfferDraft::class, inversedBy: 'contacts')]
    #[ORM\JoinColumn(nullable: true)]
    private ?OfferDraft $offerDraft;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getOffer(): ?Offer
    {
        return $this->offer;
    }

    public function setOffer(?Offer $offer): self
    {
        $this->offer = $offer;

        return $this;
    }

    public function getOfferDraft(): ?OfferDraft
    {
        return $this->offerDraft;
    }

    public function setOfferDraft(?OfferDraft $offerDraft): self
    {
        $this->offerDraft = $offerDraft;

        return $this;
    }
}
