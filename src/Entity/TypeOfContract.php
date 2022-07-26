<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\TypeOfContractRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * Class TypeOfContract
 * @package App\Entity
 */
#[ORM\Entity(repositoryClass: TypeOfContractRepository::class)]
#[ApiResource(
    collectionOperations: ['get'],
    itemOperations      : ['get']
)]
class TypeOfContract
{
    const CDI = 'CDI';
    const CDD = 'CDD';
    const INTERIM = 'INTERIM';
    const CIFRE = 'CIFRE';
    const INDIFFERENT = 'INDIFFERENT';
    const POST_DOCTORANT = 'POST-DOCTORANT';
    const CONTRAT_APPRENTISSAGE = 'CONTRAT D\'APPRENTISSAGE';
    const CONTRAT_PROFESSIONNALISATION = "CONTRAT DE PROFESSIONNALISATION";

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
     * @var Collection<int,Offer>
     */
    #[ORM\OneToMany(mappedBy: 'typeOfContract', targetEntity: Offer::class, orphanRemoval: true)]
    private Collection $offers;

    /**
     * @var Collection<int, OfferDraft>
     */
    #[ORM\OneToMany(mappedBy: 'typeOfContract', targetEntity: OfferDraft::class)]
    #[Groups(['offer:read'])]
    private Collection $offerDrafts;

    /**
     * TypeOfContract constructor.
     */
    public function __construct()
    {
        $this->offers = new ArrayCollection();
        $this->offerDrafts = new ArrayCollection();
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
     * @return Collection<int, OfferDraft>
     */
    public function getOfferDrafts(): Collection
    {
        return $this->offerDrafts;
    }

    public function addOfferDraft(OfferDraft $offerDraft): self
    {
        if (!$this->offerDrafts->contains($offerDraft)) {
            $this->offerDrafts[] = $offerDraft;
            $offerDraft->setTypeOfContract($this);
        }

        return $this;
    }

    public function removeOfferDraft(OfferDraft $offerDraft): self
    {
        if ($this->offerDrafts->removeElement($offerDraft)) {
            // set the owning side to null (unless already changed)
            if ($offerDraft->getTypeOfContract() === $this) {
                $offerDraft->setTypeOfContract(null);
            }
        }

        return $this;
    }
}
