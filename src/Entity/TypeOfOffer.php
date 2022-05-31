<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\TypeOfOfferRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 *
 */
#[ORM\Entity(repositoryClass: TypeOfOfferRepository::class)]
#[ApiResource]
class TypeOfOffer
{
    const EMPLOI_ETUDIANT = "EMPLOI_ETUDIANT";
    const EMPLOI_DEBUTANT = "EMPLOI_DEBUTANT";
    const EMPLOI_CONFIRME = "EMPLOI_CONFIRME";
    const STAGE = "STAGE";
    const APPRENTISSAGE = "APPRENTISSAGE";

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
    private string $type;

    /**
     * @var Collection<int, Offer>
     */
    #[ORM\OneToMany(mappedBy: 'typeOfOffer', targetEntity: Offer::class)]
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
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * @param string $type
     * @return $this
     */
    public function setType(string $type): self
    {
        $this->type = $type;

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
            $offer->setTypeOfOffer($this);
        }

        return $this;
    }

    public function removeOffer(Offer $offer): self
    {
        if ($this->offers->removeElement($offer)) {
            // set the owning side to null (unless already changed)
            if ($offer->getTypeOfOffer() === $this) {
                $offer->setTypeOfOffer(null);
            }
        }

        return $this;
    }
}
