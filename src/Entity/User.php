<?php

namespace App\Entity;

use DateTime;
use DateTimeInterface;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use App\Controller\User\ShowPDFAction;
use App\Controller\User\UserListAction;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Controller\User\UpdatePictureAction;
use App\Controller\User\RejectedCharteAction;
use App\Controller\User\checkFilledDatasAction;
use Doctrine\Common\Collections\ArrayCollection;
use App\Controller\User\CharteDutilisationAction;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Serializer\Annotation\Groups;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use App\Controller\User\ChangeBirthdayVisibilityAction;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use App\Controller\User\ChangeCityCountryVisibilityAction;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

/**
 *
 */
#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ApiResource(

    shortName: "users",
    denormalizationContext: [
        'groups' => [
            'user:write'
        ]
    ],
    // normalizationContext: [
    //     "groups" => [
    //         "user:read"
    //     ]
    // ],
    itemOperations      : [
        'get','put','delete', 'patch',
        'charte_user' => [
            'method' => 'POST',
            'path' => '/charteAction/{id}',
            'controller' => CharteDutilisationAction::class,
        ],
        'updatePicture' => [
            'method' => 'POST',
            'path' => '/user/{id}/updatePicture',
            'openapi_context' => [
                'summary'     => 'Use this endpoint to update only the picture of the user. Use the PUT endpoint for all other updating',
                'description' => "# Pop a great rabbit picture by color!\n\n![A great rabbit]"
                ],
            'controller' => UpdatePictureAction::class,
            'denormalization_context' => ['groups' => ['user:updatePicture']],
            'input_formats' => [
                'multipart' => ['multipart/form-data'],
            ]

         ],

         'checkFilledDatas' => [
            'method' => 'GET',
            'path' => '/checkFilledDatas/{id}',
            'controller' => checkFilledDatasAction::class,
        ],

        'rejectedCharteAction' => [
            'method' => 'PUT',
            'path' => '/rejectedCharteAction/{id}',
            'controller' => RejectedCharteAction::class,
        ],


        'showPDFAction' => [
            'method' => 'GET',
            'path' => '/showPDFAction/{id}',
            'controller' => ShowPDFAction::class,
        ],



        //  'birthday_visibility' => [
        //     'method' => 'PUT',
        //     'path' => '/changeBirthdayVisibility/user/{id}',
        //     'controller' => changeBirthdayVisibilityAction::class,
        // ],

        // 'cityAndCountry_visibility' => [
        //     'method' => 'PUT',
        //     'path' => '/changeCityAndCountryVisibility/user/{id}',
        //     'controller' => changeCityCountryVisibilityAction::class,
        // ],


        ],
    collectionOperations: [
    'get',
    'post',
    'annuaire_list' => [
        'method' => 'GET',
        'path' => '/annuaireList',
        'controller' => UserListAction::class,
    ],



],

)]
class User implements UserInterface
{
    const ROLE_ADMIN = 'ROLE_ADMIN';
    const ROLE_ETUDIANT = 'ROLE_ETUDIANT';
    const ROLE_PERSONNEL = 'ROLE_PERSONNEL';
    const ROLE_ETUDIANT_NON_INSCRIT = 'ROLE_ETUDIANT_NI';
    const ROLE_ALUMNI = 'ROLE_ALIMNI';
    const ROLE_ALUMNI_NON_REPERTORIE = 'ROLE_ALUMNI_NR';
    const ROLE_ENSEIGNANT = 'ROLE_ENSEIGNANT';

    /**
     * @var int|null
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id;



    /**
     * @var string
     */
    #[Groups(["user:write"])]
    #[ORM\Column(type: 'string', length: 180, unique: true)]
    private string $email;

    /**
     * @var string[]
     */
    #[ORM\Column(type: 'json')]
    private array $roles = [];

    #[ORM\OneToOne(mappedBy: 'user', targetEntity: CV::class, cascade: ['persist', 'remove'])]
    private ?CV $cV;

    /**
     * @var Collection<int, Notification>
     */
    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Notification::class, orphanRemoval: true)]
    private Collection $notifications;

    /**
     * @var Collection<int, SavedOfferSearch>
     */
    #[ORM\OneToMany(mappedBy: 'user', targetEntity: SavedOfferSearch::class, orphanRemoval: true)]
    private Collection $savedOfferSearches;

    /**
     * @var Collection<int, EmailNotification>
     */
    #[ORM\OneToMany(mappedBy: 'user', targetEntity: EmailNotification::class, orphanRemoval: true)]
    private Collection $emailNotifications;

    /**
     * La fréquence d'envoi des e-mails à l'utilisateur en jours
     * (1 jour pour tous les jours, 30 jours pour une fois par mois, 15 jours pour deux fois par mois, etc.)
     * @var int|null
     */
    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $frequency = 1;


    /**
     * @var boolean
     */
    #[ORM\Column(type: 'boolean',  nullable: false)]
    private bool $charteSigned = false;


      /**
     * @var DateTimeInterface|null
     */
    #[Groups(["user:write"])]
    #[ORM\Column(type: 'date', nullable: true)]
    private $birthday;

    /**
     * @var string|null
     */
    #[Groups(["user:write"])]
    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $telephone;


    /**
     * @var string|null
     */
    #[Groups(["user:write"])]
    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $firstname;

      /**
     * @var string
     */
    #[Groups(["user:write"])]
    #[ORM\Column(type: 'string', length: 255)]
    private $surname;

     /**
     * @Vich\UploadableField(mapping="media_object", fileNameProperty="imageLink")
     */
    #[Groups(['user:updatePicture'])]
    public ?File $image = null;


     /**
     * @var string
     */
    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $imageLink;


     /**
     * @var string|null
     */
    #[Groups(["user:write"])]
    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $profilTitle;


    /**
     * @var string|null
     */
    #[Groups(["user:write"])]
    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $useFirstname;


     /**
     * @var string|null
     */
    #[Groups(["user:write"])]
    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $useSurname;


      /**
     * @var string|null
     */
    #[Groups(["user:write"])]
    #[ORM\Column(type: 'text', nullable: true)]
    private $profilDescription;


     /**
     * @var boolean
     */
    #[Groups(['user:write'])]
    #[ORM\Column(type: 'boolean')]
    private $birthdayIsPublic;


     /**
     * @var string|null
     */
    #[Groups(["user:write"])]
    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $address;

     /**
     * @var string|null
     */
    #[Groups(["user:write"])]
    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $city;

     /**
     * @var string
     */
    #[Groups(["user:write"])]
    #[ORM\Column(type: 'string', length: 255)]
    private $country;

     /**
     * @var boolean
     */
    #[Groups(["user:write"])]
    #[ORM\Column(type: 'boolean')]
    private $cityAndCountryIsPublic;

     /**
     * @var boolean|null
     */
    #[Groups(["user:write"])]
    #[ORM\Column(type: 'boolean', nullable: true)]
    private $mailIsPublic;


     /**
     * @var boolean|null
     */
    #[Groups(["user:write"])]
    #[ORM\Column(type: 'boolean', nullable: true)]
    private $telephoneIsPublic;


     /**
     * @var boolean|null
     */
    #[Groups(["user:write"])]
    #[ORM\Column(type: 'boolean', nullable: true)]
    private $addressIsPublic;


      /**
     * @var bool|null
     */
    #[Groups(["user:write"])]
    #[ORM\Column(type: 'boolean', nullable: true)]
    private $datasVisibleForAllMembers;


      /**
     * @var bool|null
     */
    #[Groups(["user:write"])]
    #[ORM\Column(type: 'boolean', nullable: true)]
    private $datasVisibleForAnnuaire;


      /**
     * @var bool|null
     */
    #[Groups(["user:write"])]
    #[ORM\Column(type: 'boolean', nullable: true)]
    private $datasPublic;


      /**
     * @var bool|null
     */
    #[Groups(["user:write"])]
    #[ORM\Column(type: 'boolean', nullable: true)]
    private $datasAllPrivate;



      /**
     * @var bool|null
     */
    #[Groups(["user:write"])]
    #[ORM\Column(type: 'boolean', nullable: true)]
    private $newsLetterNotification;



      /**
     * @var bool|null
     */
    #[Groups(["user:write"])]
    #[ORM\Column(type: 'boolean', nullable: true)]
    private $rejectedCharte;


      /**
     * @var bool|null
     */
    #[Groups(["user:write"])]
    #[ORM\Column(type: 'boolean', nullable: true)]
    private $availableToWork;



    /**
     * @var Collection<int, Offer>
     */
    #[Groups(["user:read, user:write"])]
    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Offer::class)]
    private $offers;




    /**
     * @var Collection<int, Candidature>
     */
    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Candidature::class, orphanRemoval: true)]
    private Collection $candidatures;

    public function __construct()
    {
        $this->notifications = new ArrayCollection();
        $this->savedOfferSearches = new ArrayCollection();
        $this->emailNotifications = new ArrayCollection();
        $this->candidatures = new ArrayCollection();
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
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     * @return $this
     */
    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param string[] $roles
     * @return $this
     */
    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * This method can be removed in Symfony 6.0 - is not needed for apps that do not check user passwords.
     *
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): ?string
    {
        return null;
    }

    /**
     * This method can be removed in Symfony 6.0 - is not needed for apps that do not check user passwords.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials() : void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getCV(): ?CV
    {
        return $this->cV;
    }

    public function setCV(CV $cV): self
    {
        // set the owning side of the relation if necessary
        if ($cV->getUser() !== $this) {
            $cV->setUser($this);
        }

        $this->cV = $cV;

        return $this;
    }

    /**
     * @return Collection<int, Notification>
     */
    public function getNotifications(): Collection
    {
        return $this->notifications;
    }

    /**
     * @return Collection<int, SavedOfferSearch>
     */
    public function getSavedOfferSearches(): Collection
    {
        return $this->savedOfferSearches;
    }

    /**
     * @return Collection<int, EmailNotification>
     */
    public function getEmailNotifications(): Collection
    {
        return $this->emailNotifications;
    }

    /**
     * @return int|null
     */
    public function getFrequency(): ?int
    {
        return $this->frequency;
    }

    /**
     * @param int|null $frequency
     * @return User
     */
    public function setFrequency(?int $frequency): self
    {
        $this->frequency = $frequency;
        return $this;
    }

      /**
     * Set charte_signed
     *
     * @param boolean $charte_signed
     *
     * @return User
     */
    public function setCharteSigned($charte_signed) {
        $this->charteSigned = $charte_signed;

        return $this;
    }

    /**
     * Get charte_signed
     *
     * @return boolean
     */
    public function getCharteSigned() {
        return $this->charteSigned;
    }



    public function getBirthday(): ?\DateTimeInterface
    {
        return $this->birthday;
    }

    public function setBirthday(?\DateTimeInterface $birthday): self
    {
        $this->birthday = $birthday;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(?string $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(?string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getSurname(): ?string
    {
        return $this->surname;
    }

    public function setSurname(string $surname): self
    {
        $this->surname = $surname;

        return $this;
    }

     /**
     * @return File|null
     */
    public function getFile(): ?File
    {
        return $this->image;
    }

    /**
     * @param File|null $image
     */
    public function setFile(?File $image): void
    {
        $this->image = $image;
    }

    public function getImageLink(): ?string
    {
        return $this->imageLink;
    }

    public function setImageLink(?string $imageLink): self
    {
        $this->imageLink = $imageLink;

        return $this;
    }

    public function getProfilTitle(): ?string
    {
        return $this->profilTitle;
    }

    public function setProfilTitle(?string $profilTitle): self
    {
        $this->profilTitle = $profilTitle;

        return $this;
    }

    public function getUseFirstname(): ?string
    {
        return $this->useFirstname;
    }

    public function setUseFirstname(?string $useFirstname): self
    {
        $this->useFirstname = $useFirstname;

        return $this;
    }

    public function getUseSurname(): ?string
    {
        return $this->useSurname;
    }

    public function setUseSurname(?string $useSurname): self
    {
        $this->useSurname = $useSurname;

        return $this;
    }

    public function getProfilDescription(): ?string
    {
        return $this->profilDescription;
    }

    public function setProfilDescription(?string $profilDescription): self
    {
        $this->profilDescription = $profilDescription;

        return $this;
    }

    public function getBirthdayIsPublic(): ?bool
    {
        return $this->birthdayIsPublic;
    }

    public function setBirthdayIsPublic(bool $birthdayIsPublic): self
    {
        $this->birthdayIsPublic = $birthdayIsPublic;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(?string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(string $country): self
    {
        $this->country = $country;

        return $this;
    }

    public function getCityAndCountryIsPublic(): ?bool
    {
        return $this->cityAndCountryIsPublic;
    }

    public function setCityAndCountryIsPublic(bool $cityAndCountryIsPublic): self
    {
        $this->cityAndCountryIsPublic = $cityAndCountryIsPublic;

        return $this;
    }

    public function getMailIsPublic(): ?bool
    {
        return $this->mailIsPublic;
    }

    public function setMailIsPublic(?bool $mailIsPublic): self
    {
        $this->mailIsPublic = $mailIsPublic;

        return $this;
    }

    public function getTelephoneIsPublic(): ?bool
    {
        return $this->telephoneIsPublic;
    }

    public function setTelephoneIsPublic(?bool $telephoneIsPublic): self
    {
        $this->telephoneIsPublic = $telephoneIsPublic;

        return $this;
    }

    public function getAddressIsPublic(): ?bool
    {
        return $this->addressIsPublic;
    }

    public function setAddressIsPublic(?bool $addressIsPublic): self
    {
        $this->addressIsPublic = $addressIsPublic;

        return $this;
    }

    public function getDatasVisibleForAllMembers(): ?bool
    {
        return $this->datasVisibleForAllMembers;
    }

    public function setDatasVisibleForAllMembers(?bool $datasVisibleForAllMembers): self
    {
        $this->datasVisibleForAllMembers = $datasVisibleForAllMembers;

        return $this;
    }

    public function getDatasVisibleForAnnuaire(): ?bool
    {
        return $this->datasVisibleForAnnuaire;
    }

    public function setDatasVisibleForAnnuaire(?bool $datasVisibleForAnnuaire): self
    {
        $this->datasVisibleForAnnuaire = $datasVisibleForAnnuaire;

        return $this;
    }

    public function getDatasPublic(): ?bool
    {
        return $this->datasPublic;
    }

    public function setDatasPublic(?bool $datasPublic): self
    {
        $this->datasPublic = $datasPublic;

        return $this;
    }

    public function getDatasAllPrivate(): ?bool
    {
        return $this->datasAllPrivate;
    }

    public function setDatasAllPrivate(?bool $datasAllPrivate): self
    {
        $this->datasAllPrivate = $datasAllPrivate;

        return $this;
    }

    public function getNewsLetterNotification(): ?bool
    {
        return $this->newsLetterNotification;
    }

    public function setNewsLetterNotification(?bool $newsLetterNotification): self
    {
        $this->newsLetterNotification = $newsLetterNotification;

        return $this;
    }

    public function getRejectedCharte(): ?bool
    {
        return $this->rejectedCharte;
    }

    public function setRejectedCharte(?bool $rejectedCharte): self
    {
        $this->rejectedCharte = $rejectedCharte;

        return $this;
    }

    public function getAvailableToWork(): ?bool
    {
        return $this->availableToWork;
    }

    public function setAvailableToWork(?bool $availableToWork): self
    {
        $this->availableToWork = $availableToWork;

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
            $offer->setUser($this);
        }

        return $this;
    }

    public function removeOffer(Offer $offer): self
    {
        if ($this->offers->removeElement($offer)) {
            // set the owning side to null (unless already changed)
            if ($offer->getUser() === $this) {
                $offer->setUser($this);
            }
        }

        return $this;
    }



    /**
     * @return Collection<int, Candidature>
     */
    public function getCandidatures(): Collection
    {
        return $this->candidatures;
    }

}
