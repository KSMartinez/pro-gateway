<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\DomainRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * Class Domain
 * @package App\Entity
 */
#[ORM\Entity(repositoryClass: DomainRepository::class)]
#[ApiResource(
    collectionOperations: ['get'],
    itemOperations      : ['get'])
]
class Domain
{
    const ART_CULTURE_AUDIOVISUEL = "ART_CULTURE_AUDIOVISUEL";
    const ACTIVITES_JURIDIQUES = "ACTIVITES_JURIDIQUES";
    const ADMINISTRATIONS_EUROPEENNES_ET_INTERNATIONALES = "ADMINISTRATIONS_EUROPEENNES_ET_INTERNATIONALES";
    const BTP_GENIE_CIVIL_IMMOBILIER = "BTP_GENIE_CIVIL_IMMOBILIER";
    const COMMERCIAL_MARKETING = "COMMERCIAL_MARKETING";
    const COMMUNICATION_MULTIMEDIA_DIGITAL_JOURNALISME = "COMMUNICATION_MULTIMEDIA_DIGITAL_JOURNALISME";
    const ENSEIGNEMENT_RECHERCHE_FORMATION = "ENSEIGNEMENT_RECHERCHE_FORMATION";
    const ETUDES_RECHERCHES_DEVELOPPEMENT_BIOLOGIE_PHYSIQUE_CHIMIE_MATHEMATIQUES = "ETUDES_RECHERCHES_DEVELOPPEMENT_BIOLOGIE_PHYSIQUE_CHIMIE_MATHEMATIQUES";
    const FINANCE_GESTION_AUDIT_BANQUE_ASSURANCE = "FINANCE_GESTION_AUDIT_BANQUE_ASSURANCE";
    const FONCTION_PUBLIQUE_ETAT_TERRITORIALE_HOSPITALIERE = "FONCTION_PUBLIQUE_ETAT_TERRITORIALE_HOSPITALIERE";
    const GENIE_CIVIL_GENIE_ELECTRIQUE_ENERGIE = "GENIE_CIVIL_GENIE_ELECTRIQUE_ENERGIE";
    const INFORMATIQUE_SI_RESEAUX = "INFORMATIQUE_SI_RESEAUX";
    const MANAGEMENT_CONSEIL_STRATEGIE_COACHING = "MANAGEMENT_CONSEIL_STRATEGIE_COACHING";
    const QUALITE_ENVIRONNEMENT_SECURITE = "QUALITE_ENVIRONNEMENT_SECURITE";
    const RESTAURATION_HOTELLERIE_TOURISME = "RESTAURATION_HOTELLERIE_TOURISME";
    const RH_SOCIOLOGIE_PSYCHOLOGIE = "RH_SOCIOLOGIE_PSYCHOLOGIE";
    const SANTE_SOCIAL_MEDECINE = "SANTE_SOCIAL_MEDECINE";
    const SPORT = "SPORT";
    const TRANSPORT_LOGISTIQUE = "TRANSPORT_LOGISTIQUE";

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
    #[ORM\OneToMany(mappedBy: 'domain', targetEntity: Offer::class)]
    private Collection $offers;

    /**
     * Domain constructor.
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
            $offer->setDomain($this);
        }

        return $this;
    }

    /**
     * @param Offer $offer
     * @return $this
     */
    public function removeOffer(Offer $offer): self
    {
        if ($this->offers->removeElement($offer)) {
            // set the owning side to null (unless already changed)
            if ($offer->getDomain() === $this) {
                $offer->setDomain(null);
            }
        }

        return $this;
    }
}
