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
     * TypeOfContract constructor.
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
}
