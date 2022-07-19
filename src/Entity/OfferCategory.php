<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\OfferCategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 *
 */
#[ORM\Entity(repositoryClass: OfferCategoryRepository::class)]
#[ApiResource]
class OfferCategory
{
    const EMPLOI_ETUDIANT = "Job étudiant";
    const VIE_VIA = "VIE/VIA";
    const STAGE = "Stage";
    const ALTERNANCE = "Alternance";
    const EMPLOI = "Emploi";
    const SERVICE_CIVIQUE = "Service Civique";

    /**
     * @var int|null
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['offer:read'])]
    private ?int $id = null;

    /**
     * @var string
     */
    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['offer:read'])]
    private string $label;

    /**
     * @var Collection<int, Offer>
     */
    #[ORM\OneToMany(mappedBy: 'offerCategory', targetEntity: Offer::class)]
    private Collection $offers;

    public function __construct()
    {
        $this->offers = new ArrayCollection();
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
    public function getLabel(): ?string
    {
        return $this->label;
    }

    /**
     * @param string $label
     * @return $this
     */
    public function setLabel(string $label): self
    {
        $this->label = $label;

        return $this;
    }

    /**
     * @return Collection<int, Offer>
     */
    public function getOffers(): Collection
    {
        return $this->offers;
    }

    public function addOffer(Offer $offer): self
    {
        if (!$this->offers->contains($offer)) {
            $this->offers[] = $offer;
            $offer->setOfferCategory($this);
        }

        return $this;
    }

    public function removeOffer(Offer $offer): self
    {
        if ($this->offers->removeElement($offer)) {
            // set the owning side to null (unless already changed)
            if ($offer->getOfferCategory() === $this) {
                $offer->setOfferCategory(null);
            }
        }

        return $this;
    }
}
