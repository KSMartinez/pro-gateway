<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\SectorOfOfferRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 *
 */
#[ORM\Entity(repositoryClass: SectorOfOfferRepository::class)]
#[ApiResource]
class SectorOfOffer
{


    const CULTURE_ET_PRODUCTION_ANIMALE_CHASSE_ET_SERVICES_ANNEXES = "Culture et production animale, chasse et services annexes";
    const SYLVICULTURE_ET_EXPLOITATION_FORESTIERE = "Sylviculture et exploitation forestiere";
    const PECHE_ET_AQUACULTURE = "Peche et aquaculture";
    const EXTRACTION_DE_HOUILLE_ET_DE_LIGNITE = "Extraction de houille et de lignite";
    const EXTRACTION_D_HYDROCARBURES = "Extraction d'hydrocarbures";
    const EXTRACTION_DE_MINERAIS_METALLIQUES = "Extraction de minerais metalliques";
    const AUTRES_INDUSTRIES_EXTRACTIVES = "Autres industries extractives";
    const SERVICES_DE_SOUTIEN_AUX_INDUSTRIES_EXTRACTIVES = "Services de soutien aux industries extractives";
    const INDUSTRIES_ALIMENTAIRES = "Industries alimentaires";
    const FABRICATION_DE_BOISSONS = "Fabrication de boissons";
    const FABRICATION_DE_PRODUITS_A_BASE_DE_TABAC = "Fabrication de produits a base de tabac";
    const FABRICATION_DE_TEXTILES = "Fabrication de textiles";
    const INDUSTRIE_DE_L_HABILLEMENT = "Industrie de l'habillement";
    const INDUSTRIE_DU_CUIR_ET_DE_LA_CHAUSSURE = "Industrie du cuir et de la chaussure";
    const TRAVAIL_DU_BOIS_ET_FABRICATION_D_ARTICLES_EN_BOIS_ET_EN_LIEGE = "Travail du bois et fabrication d'articles en bois et en liege, a l'exception des meubles ;fabrication d'articles en vannerie et sparterie";
    const INDUSTRIE_DU_PAPIER_ET_DU_CARTON = "Industrie du papier et du carton";
    const IMPRIMERIE_ET_REPRODUCTION_D_ENREGISTREMENTS = "Imprimerie et reproduction d'enregistrements";
    const COKEFACTION_ET_RAFFINAGE = "Cokefaction et raffinage";
    const INDUSTRIE_CHIMIQUE = "Industrie chimique";
    const INDUSTRIE_PHARMACEUTIQUE = "Industrie pharmaceutique";
    const FABRICATION_DE_PRODUITS_EN_CAOUTCHOUC_ET_EN_PLASTIQUE = "Fabrication de produits en caoutchouc et en plastique";
    const FABRICATION_D_AUTRES_PRODUITS_MINERAUX_NON_METALLIQUES = "Fabrication d'autres produits mineraux non metalliques";
    const METALLURGIE = "Metallurgie";
    const FABRICATION_DE_PRODUITS_METALLIQUES_A_L_EXCEPTION_DES_MACHINES_ET_DES_EQUIPEMENTS = "Fabrication de produits metalliques, a l'exception des machines et des equipements";
    const FABRICATION_DE_PRODUITS_INFORMATIQUES_ELECTRONIQUES_ET_OPTIQUES = "Fabrication de produits informatiques, electroniques et optiques";
    const FABRICATION_D_EQUIPEMENTS_ELECTRIQUES = "Fabrication d'equipements electriques";
    const FABRICATION_DE_MACHINES_ET_EQUIPEMENTS_N_C_A = "Fabrication de machines et equipements n.c.a.";
    const INDUSTRIE_AUTOMOBILE = "Industrie automobile";
    const FABRICATION_D_AUTRES_MATERIELS_DE_TRANSPORT = "Fabrication d'autres materiels de transport";
    const FABRICATION_DE_MEUBLES = "Fabrication de meubles";
    const AUTRES_INDUSTRIES_MANUFACTURIERES = "Autres industries manufacturieres";
    const REPARATION_ET_INSTALLATION_DE_MACHINES_ET_D_EQUIPEMENTS = "Reparation et installation de machines et d'equipements";
    const PRODUCTION_ET_DISTRIBUTION_D_ELECTRICITE_DE_GAZ_DE_VAPEUR_ET_D_AIR_CONDITIONNE = "Production et distribution d'electricite, de gaz, de vapeur et d'air conditionne";
    const CAPTAGE_TRAITEMENT_ET_DISTRIBUTION_D_EAU = "Captage, traitement et distribution d'eau";
    const COLLECTE_ET_TRAITEMENT_DES_EAUX_USEES = "Collecte et traitement des eaux usees";
    const COLLECTE_TRAITEMENT_ET_ELIMINATION_DES_DECHETS_RECUPERATION = "Collecte, traitement et elimination des dechets ; recuperation";
    const DEPOLLUTION_ET_AUTRES_SERVICES_DE_GESTION_DES_DECHETS = "Depollution et autres services de gestion des dechets";
    const CONSTRUCTION_DE_BATIMENTS = "Construction de batiments";
    const GENIE_CIVIL = "Genie civil";
    const TRAVAUX_DE_CONSTRUCTION_SPECIALISES = "Travaux de construction specialises";
    const COMMERCE_ET_REPARATION_D_AUTOMOBILES_ET_DE_MOTOCYCLES = "Commerce et reparation d'automobiles et de motocycles";
    const COMMERCE_DE_GROS_A_L_EXCEPTION_DES_AUTOMOBILES_ET_DES_MOTOCYCLES = "Commerce de gros, a l'exception des automobiles et des motocycles";
    const COMMERCE_DE_DETAIL_A_L_EXCEPTION_DES_AUTOMOBILES_ET_DES_MOTOCYCLES = "Commerce de detail, a l'exception des automobiles et des motocycles";
    const TRANSPORTS_TERRESTRES_ET_TRANSPORT_PAR_CONDUITES = "Transports terrestres et transport par conduites";
    const TRANSPORTS_PAR_EAU = "Transports par eau";
    const TRANSPORTS_AERIENS = "Transports aeriens";
    const ENTREPOSAGE_ET_SERVICES_AUXILIAIRES_DES_TRANSPORTS = "Entreposage et services auxiliaires des transports";
    const ACTIVITES_DE_POSTE_ET_DE_COURRIER = "Activites de poste et de courrier";
    const HEBERGEMENT = "Hebergement";
    const RESTAURATION = "Restauration";
    const EDITION = "Edition";
    const PRODUCTION_DE_FILMS_CINEMATOGRAPHIQUES_DE_VIDEO_ET_DE_PROGRAMMES_DE_TELEVISION_ENREGISTREMENT_SONORE_ET_EDITION_MUSICALE = "Production de films cinematographiques, de video et de programmes de television ;enregistrement sonore et edition musicale";
    const PROGRAMMATION_ET_DIFFUSION = "Programmation et diffusion";
    const TELECOMMUNICATIONS = "Telecommunications";
    const PROGRAMMATION_CONSEIL_ET_AUTRES_ACTIVITES_INFORMATIQUES = "Programmation, conseil et autres activites informatiques";
    const SERVICES_D_INFORMATION = "Services d'information";
    const ACTIVITES_DES_SERVICES_FINANCIERS_HORS_ASSURANCE_ET_CAISSES_DE_RETRAITE = "Activites des services financiers, hors assurance et caisses de retraite";
    const ASSURANCE = "Assurance";
    const ACTIVITES_AUXILIAIRES_DE_SERVICES_FINANCIERS_ET_D_ASSURANCE = "Activites auxiliaires de services financiers et d'assurance";
    const ACTIVITES_IMMOBILIERES = "Activites immobilieres";
    const ACTIVITES_JURIDIQUES_ET_COMPTABLES = "Activites juridiques et comptables";
    const ACTIVITES_DES_SIEGES_SOCIAUX_CONSEIL_DE_GESTION = "Activites des sieges sociaux ; conseil de gestion";
    const ACTIVITES_D_ARCHITECTURE_ET_D_INGENIERIE_ACTIVITES_DE_CONTROLE_ET_ANALYSES_TECHNIQUES = "Activites d'architecture et d'ingenierie ; activites de controle et analyses techniques";
    const RECHERCHE_DEVELOPPEMENT_SCIENTIFIQUE = "Recherche-developpement scientifique";
    const PUBLICITE_ET_ETUDES_DE_MARCHE = "Publicite et etudes de marche";
    const AUTRES_ACTIVITES_SPECIALISEES_SCIENTIFIQUES_ET_TECHNIQUES = "Autres activites specialisees, scientifiques et techniques";
    const ACTIVITES_VETERINAIRES = "Activites veterinaires";
    const ACTIVITES_DE_LOCATION_ET_LOCATION_BAIL = "Activites de location et location-bail";
    const ACTIVITES_LIEES_A_L_EMPLOI = "Activites liees a l'emploi";
    const ACTIVITES_DES_AGENCES_DE_VOYAGE_VOYAGISTES_SERVICES_DE_RESERVATION_ET_ACTIVITES_CONNEXES = "Activites des agences de voyage, voyagistes, services de reservation et activites connexes";
    const ENQUETES_ET_SECURITE = "Enquetes et securite";
    const SERVICES_RELATIFS_AUX_BATIMENTS_ET_AMENAGEMENT_PAYSAGER = "Services relatifs aux batiments et amenagement paysager";
    const ACTIVITES_ADMINISTRATIVES_ET_AUTRES_ACTIVITES_DE_SOUTIEN_AUX_ENTREPRISES = "Activites administratives et autres activites de soutien aux entreprises";
    const ADMINISTRATION_PUBLIQUE_ET_DEFENSE_SECURITE_SOCIALE_OBLIGATOIRE = "Administration publique et defense ; securite sociale obligatoire";
    const ENSEIGNEMENT = "Enseignement";
    const ACTIVITES_POUR_LA_SANTE_HUMAINE = "Activites pour la sante humaine";
    const HEBERGEMENT_MEDICO_SOCIAL_ET_SOCIAL = "Hebergement medico-social et social";
    const ACTION_SOCIALE_SANS_HEBERGEMENT = "Action sociale sans hebergement";
    const ACTIVITES_CREATIVES_ARTISTIQUES_ET_DE_SPECTACLE = "Activites creatives, artistiques et de spectacle";
    const BIBLIOTHEQUES_ARCHIVES_MUSEES_ET_AUTRES_ACTIVITES_CULTURELLES = "Bibliotheques, archives, musees et autres activites culturelles";
    const ORGANISATION_DE_JEUX_DE_HASARD_ET_D_ARGENT = "Organisation de jeux de hasard et d'argent";
    const ACTIVITES_SPORTIVES_RECREATIVES_ET_DE_LOISIRS = "Activites sportives, recreatives et de loisirs";
    const ACTIVITES_DES_ORGANISATIONS_ASSOCIATIVES = "Activites des organisations associatives";
    const REPARATION_D_ORDINATEURS_ET_DE_BIENS_PERSONNELS_ET_DOMESTIQUES = "Reparation d'ordinateurs et de biens personnels et domestiques";
    const AUTRES_SERVICES_PERSONNELS = "Autres services personnels";
    const ACTIVITES_DES_MENAGES_EN_TANT_QU_EMPLOYEURS_DE_PERSONNEL_DOMESTIQUE = "Activites des menages en tant qu'employeurs de personnel domestique";
    const ACTIVITES_INDIFFERENCIEES_DES_MENAGES_EN_TANT_QUE_PRODUCTEURS_DE_BIENS_ET_SERVICES_POUR_USAGE_PROPRE = "Activites indifferenciees des menages en tant que producteurs de biens et services pour usage propre";
    const ACTIVITES_DES_ORGANISATIONS_ET_ORGANISMES_EXTRATERRITORIAUX = "Activites des organisations et organismes extraterritoriaux";



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
    #[ORM\OneToMany(mappedBy: 'sector', targetEntity: Offer::class)]
    private Collection $offers;

    /**
     * @var Collection<int, OfferDraft>
     */
    #[ORM\OneToMany(mappedBy: 'sector', targetEntity: OfferDraft::class)]
    private Collection $offerDrafts;

    public function __construct()
    {
        $this->offerDrafts = new ArrayCollection();
    }


    /**
     * @return void
     */
    public function _construct()
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
            $offer->setSector($this);
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
            if ($offer->getSector() === $this) {
                $offer->setSector(null);
            }
        }

        return $this;
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
            $offerDraft->setSector($this);
        }

        return $this;
    }

    public function removeOfferDraft(OfferDraft $offerDraft): self
    {
        if ($this->offerDrafts->removeElement($offerDraft)) {
            // set the owning side to null (unless already changed)
            if ($offerDraft->getSector() === $this) {
                $offerDraft->setSector(null);
            }
        }

        return $this;
    }
}
