<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\LevelOfEducationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 *
 */
#[ORM\Entity(repositoryClass: LevelOfEducationRepository::class)]
#[ApiResource]
class LevelOfEducation
{

    const INDIFFERENT = "Indifférent";
    const BAC = "Bac";
    const BAC1 = "Bac+1";
    const BAC2 = "Bac+2";
    const BAC3 = "Bac+3";
    const BAC4 = "Bac+4";
    const BAC5 = "Bac+5";
    const ABOVE_BAC5 = "> Bac+5";

    /**
     * @var int|null
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['offer:read'])]
    private ?int $id;

    /**
     * @var string
     */
    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['offer:read'])]
    private string $label;

    /**
     * @var Collection<int, Offer>
     */
    #[ORM\ManyToMany(targetEntity: Offer::class, mappedBy: 'levelOfEducations')]
    private Collection $offers;

    /**
     *
     */
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

    /**
     * @param Offer $offer
     * @return $this
     */
    public function addOffer(Offer $offer): self
    {
        if (!$this->offers->contains($offer)) {
            $this->offers[] = $offer;
            $offer->addLevelOfEducation($this);
        }

        return $this;
    }

    /**
     * @param Offer $offer
     * @return LevelOfEducation
     */
    public function removeOffer(Offer $offer): LevelOfEducation
    {
        if ($this->offers->contains($offer)){
            $this->offers->removeElement($offer);
            $offer->removeLevelOfEducation($this);
        }

        return $this;
    }
}
