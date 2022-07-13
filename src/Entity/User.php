<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use App\Controller\User\CharteDutilisationAction;
use App\Controller\User\checkFilledDatasAction;
use App\Controller\User\RejectedCharteAction;
use App\Controller\User\ShowPDFAction;
use App\Controller\User\ShowPDFActionCopy;
use App\Controller\User\UpdatePictureAction;
use App\Controller\User\UserEventsAction;
use App\Controller\User\UserListAction;
use App\Filters\CompanyExperienceAnnuaireFilter;
use App\Repository\UserRepository;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use App\Entity\News;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ApiFilter(SearchFilter::class, properties={
 *     "charteSigned": "exact",
 *     "datasVisibleForAnnuaire": "exact",
 *     "roles": "exact",
 *     "profilTitle":"partial",
 *     "profilDescription":"partial",
 *     "surname":"partial",
 *     "firstname":"partial",
 *     "address":"partial",
 *     "city":"partial",
 *     "country":"partial",
 *     "companyCreator":"exact"
 * })
 * @ApiFilter(CompanyExperienceAnnuaireFilter::class)
 * @ApiFilter(OrderFilter::class, properties={"surname" : "ASC"})
 */
#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ApiResource(

    collectionOperations  : [
    'get',
    'post',
    'annuaire_list' => [
        'method' => 'GET',
        'path' => '/annuaireList',
        'controller' => UserListAction::class,
    ],



],
    itemOperations        : [
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
                'summary'     => 'Use this endpoint to update only the picture of the user. Use the PUT endpoint for all other updating'
                ],
            'controller' => UpdatePictureAction::class,
            'denormalization_context' => ['groups' => ['user:updatePicture']],
            'input_formats' => [
                'multipart' => ['multipart/form-data'],
                'json' => ['application/json'],
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


    'user_events' => [
        'method' => 'GET',
        'path' => '/userEvents/{id}',
        'controller' => UserEventsAction::class,
    ],



        ],
     /*normalizationContext: [
         "groups" => [
             "user:read"
         ]
     ],*/
    shortName             : "users",
    denormalizationContext: [
        'groups' => [
            'user:write'
        ]
    ],

)]
/**
 * @Vich\Uploadable()
 */
class User implements UserInterface
{
    const ROLE_ADMIN = 'ROLE_ADMIN';
    const ROLE_ETUDIANT = 'ROLE_ETUDIANT';
    const ROLE_PERSONNEL = 'ROLE_PERSONNEL';
    const ROLE_ETUDIANT_NON_INSCRIT = 'ROLE_ETUDIANT_NI';
    const ROLE_ALUMNI = 'ROLE_ALUMNI';
    const ROLE_ALUMNI_NON_REPERTORIE = 'ROLE_ALUMNI_NR';
    const ROLE_ENSEIGNANT = 'ROLE_ENSEIGNANT';

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
    #[Groups(["user:write"])]
    #[ORM\Column(type: 'string', length: 180, unique: true)]
    private string $email;

    /**
     * @var string[]
     */
    #[Groups(["user:write"])]
    #[ORM\Column(type: 'json')]
    private array $roles = [];

    /**
     * @var CV|null
     */
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
    private ?DateTimeInterface $birthday;

    /**
     * @var string|null
     */
    #[Groups(["user:write"])]
    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $telephone;


    /**
     * @var string|null
     */
    #[Groups(["user:write"])]
    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $firstname;

      /**
     * @var string
     */
    #[Groups(["user:write"])]
    #[ORM\Column(type: 'string', length: 255)]
    private string $surname;

     /**
     * @Vich\UploadableField(mapping="media_object", fileNameProperty="imageLink")
     */
    #[Groups(['user:updatePicture'])]
    #[Assert\File(
        maxSize: '1024k',
        mimeTypes: ['image/png', 'image/jpeg', 'image/webp'],
    )]
    #[Assert\Image(
        allowSquare: true,
        allowLandscape: false,
        allowPortrait: false,
    )]
    public ?File $image = null;

     /**
     * @var string|null
     */
    /*#[Groups(['user:read'])]*/
    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $imageLink;


     /**
     * @var string|null
     */
    #[Groups(["user:write"])]
    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $profilTitle;


    /**
     * @var string|null
     */
    #[Groups(["user:write"])]
    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $useFirstname;


     /**
     * @var string|null
     */
    #[Groups(["user:write"])]
    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $useSurname;


      /**
     * @var string|null
     */
    #[Groups(["user:write"])]
    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $profilDescription;


     /**
     * @var boolean
     */
    #[Groups(['user:write'])]
    #[ORM\Column(type: 'boolean')]
    private bool $birthdayIsPublic = false;


     /**
     * @var string|null
     */
    #[Groups(["user:write"])]
    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $address;

     /**
     * @var string|null
     */
    #[Groups(["user:write"])]
    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $city;

     /**
     * @var string
     */
    #[Groups(["user:write"])]
    #[ORM\Column(type: 'string', length: 255)]
    private string $country;

     /**
     * @var boolean
     */
    #[Groups(["user:write"])]
    #[ORM\Column(type: 'boolean')]
    private bool $cityAndCountryIsPublic = false;

     /**
     * @var boolean|null
     */
    #[Groups(["user:write"])]
    #[ORM\Column(type: 'boolean', nullable: true)]
    private ?bool $mailIsPublic;


     /**
     * @var boolean|null
     */
    #[Groups(["user:write"])]
    #[ORM\Column(type: 'boolean', nullable: true)]
    private ?bool $telephoneIsPublic;


     /**
     * @var boolean|null
     */
    #[Groups(["user:write"])]
    #[ORM\Column(type: 'boolean', nullable: true)]
    private ?bool $addressIsPublic;


      /**
     * @var bool|null
     */
    #[Groups(["user:write"])]
    #[ORM\Column(type: 'boolean', nullable: true)]
    private ?bool $datasVisibleForAllMembers;


      /**
     * @var bool|null
     */
    #[Groups(["user:write"])]
    #[ORM\Column(type: 'boolean', nullable: true)]
    private ?bool $datasVisibleForAnnuaire;


      /**
     * @var bool|null
     */
    #[Groups(["user:write"])]
    #[ORM\Column(type: 'boolean', nullable: true)]
    private ?bool $datasPublic;


      /**
     * @var bool|null
     */
    #[Groups(["user:write"])]
    #[ORM\Column(type: 'boolean', nullable: true)]
    private ?bool $datasAllPrivate;



      /**
     * @var bool|null
     */
    #[Groups(["user:write"])]
    #[ORM\Column(type: 'boolean', nullable: true)]
    private ?bool $newsLetterNotification;



      /**
     * @var bool|null
     */
    #[Groups(["user:write"])]
    #[ORM\Column(type: 'boolean', nullable: true)]
    private ?bool $rejectedCharte;


      /**
     * @var bool|null
     */
    #[Groups(["user:write"])]
    #[ORM\Column(type: 'boolean', nullable: true)]
    private ?bool $availableToWork;


    /**
     * @var Collection<int, Candidature>
     */
    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Candidature::class, orphanRemoval: true)]
    private Collection $candidatures;

    /**
     * @var Collection<int, News>
     */
    #[ORM\OneToMany(mappedBy: 'user', targetEntity: News::class, orphanRemoval: true)]
    private Collection $news;



    /**
     * @var bool|null
     */
    #[ORM\Column(type: 'boolean', nullable:true)]
    #[Groups(["user:write"])]
    private ?bool $companyCreator;

    /**
     * @var string|null
     */
    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Groups(["user:write"])]
    private ?string $linkedinAccount;

    /**
     * @var string|null
     */
    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Groups(["user:write"])]
    private ?string $facebookAccount;

    /**
     * @var string|null
     */
    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Groups(["user:write"])]
    private ?string $instagramAccount;

    /**
     * @var string|null
     */
    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Groups(["user:write"])]
    private ?string $twitterAccount;

    /**
     * @var bool|null
     */
    #[ORM\Column(type: 'boolean', nullable: true)]
    #[Groups(["user:write"])]
    private ?bool $mentorAccept;

    /**
     * @var Collection<int, GroupMember>
     */
    #[ORM\OneToMany(mappedBy: 'user', targetEntity: GroupMember::class, orphanRemoval: true)]
    private Collection $groupsMemberOf;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    private ?\DateTimeImmutable $updatedAt;

    /**
     * @var Collection<int, Conversation>
     */
    #[ORM\ManyToMany(targetEntity: Conversation::class, mappedBy: 'users')]
    private Collection $conversations;


      /**
     * User constructor.
     */
    public function __construct()
    {
        $this->notifications = new ArrayCollection();
        $this->savedOfferSearches = new ArrayCollection();
        $this->emailNotifications = new ArrayCollection();
        $this->candidatures = new ArrayCollection();
        $this->groupsMemberOf = new ArrayCollection();
        $this->conversations = new ArrayCollection();
        $this->news = new ArrayCollection();


    }

    /**
     * @return array<Group>
     */
    public function getGroups(): array
    {
        $groups = [];
        /** @var GroupMember[] $groupsMemberOf */
        $groupsMemberOf = $this->getGroupsMemberOf();
        foreach ($groupsMemberOf as $groupMemberOf) {
            $groups[] = $groupMemberOf->getGroupOfMember();
        }

        return $groups;

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

    /**
     * @return CV|null
     */
    public function getCV(): ?CV
    {
        return $this->cV;
    }

    /**
     * @param CV $cV
     * @return $this
     */
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


    /**
     * @return DateTimeInterface|null
     */
    public function getBirthday(): ?\DateTimeInterface
    {
        return $this->birthday;
    }

    /**
     * @param DateTimeInterface|null $birthday
     * @return $this
     */
    public function setBirthday(?\DateTimeInterface $birthday): self
    {
        $this->birthday = $birthday;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    /**
     * @param string|null $telephone
     * @return $this
     */
    public function setTelephone(?string $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    /**
     * @param string|null $firstname
     * @return $this
     */
    public function setFirstname(?string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getSurname(): ?string
    {
        return $this->surname;
    }

    /**
     * @param string $surname
     * @return $this
     */
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

    /**
     * @return string|null
     */
    public function getImageLink(): ?string
    {
        return $this->imageLink;
    }

    /**
     * @param string|null $imageLink
     * @return $this
     */
    public function setImageLink(?string $imageLink): self
    {
        $this->imageLink = $imageLink;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getProfilTitle(): ?string
    {
        return $this->profilTitle;
    }

    /**
     * @param string|null $profilTitle
     * @return $this
     */
    public function setProfilTitle(?string $profilTitle): self
    {
        $this->profilTitle = $profilTitle;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getUseFirstname(): ?string
    {
        return $this->useFirstname;
    }

    /**
     * @param string|null $useFirstname
     * @return $this
     */
    public function setUseFirstname(?string $useFirstname): self
    {
        $this->useFirstname = $useFirstname;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getUseSurname(): ?string
    {
        return $this->useSurname;
    }

    /**
     * @param string|null $useSurname
     * @return $this
     */
    public function setUseSurname(?string $useSurname): self
    {
        $this->useSurname = $useSurname;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getProfilDescription(): ?string
    {
        return $this->profilDescription;
    }

    /**
     * @param string|null $profilDescription
     * @return $this
     */
    public function setProfilDescription(?string $profilDescription): self
    {
        $this->profilDescription = $profilDescription;

        return $this;
    }

    /**
     * @return bool|null
     */
    public function getBirthdayIsPublic(): ?bool
    {
        return $this->birthdayIsPublic;
    }

    /**
     * @param bool $birthdayIsPublic
     * @return $this
     */
    public function setBirthdayIsPublic(bool $birthdayIsPublic): self
    {
        $this->birthdayIsPublic = $birthdayIsPublic;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getAddress(): ?string
    {
        return $this->address;
    }

    /**
     * @param string|null $address
     * @return $this
     */
    public function setAddress(?string $address): self
    {
        $this->address = $address;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getCity(): ?string
    {
        return $this->city;
    }

    /**
     * @param string|null $city
     * @return $this
     */
    public function setCity(?string $city): self
    {
        $this->city = $city;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getCountry(): ?string
    {
        return $this->country;
    }

    /**
     * @param string $country
     * @return $this
     */
    public function setCountry(string $country): self
    {
        $this->country = $country;

        return $this;
    }

    /**
     * @return bool|null
     */
    public function getCityAndCountryIsPublic(): ?bool
    {
        return $this->cityAndCountryIsPublic;
    }

    /**
     * @param bool $cityAndCountryIsPublic
     * @return $this
     */
    public function setCityAndCountryIsPublic(bool $cityAndCountryIsPublic): self
    {
        $this->cityAndCountryIsPublic = $cityAndCountryIsPublic;

        return $this;
    }

    /**
     * @return bool|null
     */
    public function getMailIsPublic(): ?bool
    {
        return $this->mailIsPublic;
    }

    /**
     * @param bool|null $mailIsPublic
     * @return $this
     */
    public function setMailIsPublic(?bool $mailIsPublic): self
    {
        $this->mailIsPublic = $mailIsPublic;

        return $this;
    }

    /**
     * @return bool|null
     */
    public function getTelephoneIsPublic(): ?bool
    {
        return $this->telephoneIsPublic;
    }

    /**
     * @param bool|null $telephoneIsPublic
     * @return $this
     */
    public function setTelephoneIsPublic(?bool $telephoneIsPublic): self
    {
        $this->telephoneIsPublic = $telephoneIsPublic;

        return $this;
    }

    /**
     * @return bool|null
     */
    public function getAddressIsPublic(): ?bool
    {
        return $this->addressIsPublic;
    }

    /**
     * @param bool|null $addressIsPublic
     * @return $this
     */
    public function setAddressIsPublic(?bool $addressIsPublic): self
    {
        $this->addressIsPublic = $addressIsPublic;

        return $this;
    }

    /**
     * @return bool|null
     */
    public function getDatasVisibleForAllMembers(): ?bool
    {
        return $this->datasVisibleForAllMembers;
    }

    /**
     * @param bool|null $datasVisibleForAllMembers
     * @return $this
     */
    public function setDatasVisibleForAllMembers(?bool $datasVisibleForAllMembers): self
    {
        $this->datasVisibleForAllMembers = $datasVisibleForAllMembers;

        return $this;
    }

    /**
     * @return bool|null
     */
    public function getDatasVisibleForAnnuaire(): ?bool
    {
        return $this->datasVisibleForAnnuaire;
    }

    /**
     * @param bool|null $datasVisibleForAnnuaire
     * @return $this
     */
    public function setDatasVisibleForAnnuaire(?bool $datasVisibleForAnnuaire): self
    {
        $this->datasVisibleForAnnuaire = $datasVisibleForAnnuaire;

        return $this;
    }

    /**
     * @return bool|null
     */
    public function getDatasPublic(): ?bool
    {
        return $this->datasPublic;
    }

    /**
     * @param bool|null $datasPublic
     * @return $this
     */
    public function setDatasPublic(?bool $datasPublic): self
    {
        $this->datasPublic = $datasPublic;

        return $this;
    }

    /**
     * @return bool|null
     */
    public function getDatasAllPrivate(): ?bool
    {
        return $this->datasAllPrivate;
    }

    /**
     * @param bool|null $datasAllPrivate
     * @return $this
     */
    public function setDatasAllPrivate(?bool $datasAllPrivate): self
    {
        $this->datasAllPrivate = $datasAllPrivate;

        return $this;
    }

    /**
     * @return bool|null
     */
    public function getNewsLetterNotification(): ?bool
    {
        return $this->newsLetterNotification;
    }

    /**
     * @param bool|null $newsLetterNotification
     * @return $this
     */
    public function setNewsLetterNotification(?bool $newsLetterNotification): self
    {
        $this->newsLetterNotification = $newsLetterNotification;

        return $this;
    }

    /**
     * @return bool|null
     */
    public function getRejectedCharte(): ?bool
    {
        return $this->rejectedCharte;
    }

    /**
     * @param bool|null $rejectedCharte
     * @return $this
     */
    public function setRejectedCharte(?bool $rejectedCharte): self
    {
        $this->rejectedCharte = $rejectedCharte;

        return $this;
    }

    /**
     * @return bool|null
     */
    public function getAvailableToWork(): ?bool
    {
        return $this->availableToWork;
    }

    /**
     * @param bool|null $availableToWork
     * @return $this
     */
    public function setAvailableToWork(?bool $availableToWork): self
    {
        $this->availableToWork = $availableToWork;

        return $this;
    }



    /**
     * @return Collection<int, Candidature>
     */
    public function getCandidatures(): Collection
    {
        return $this->candidatures;
    }

    /**
     * @return Collection<int, News>
     */
    public function getNews(): Collection
    {
        return $this->news;
    }


    /**
     * @return bool|null
     */
    public function getCompanyCreator(): ?bool
    {
        return $this->companyCreator;
    }

    /**
     * @param bool $companyCreator
     * @return $this
     */
    public function setCompanyCreator(bool $companyCreator): self
    {
        $this->companyCreator = $companyCreator;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getLinkedinAccount(): ?string
    {
        return $this->linkedinAccount;
    }

    /**
     * @param string|null $linkedinAccount
     * @return $this
     */
    public function setLinkedinAccount(?string $linkedinAccount): self
    {
        $this->linkedinAccount = $linkedinAccount;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getFacebookAccount(): ?string
    {
        return $this->facebookAccount;
    }

    /**
     * @param string|null $facebookAccount
     * @return $this
     */
    public function setFacebookAccount(?string $facebookAccount): self
    {
        $this->facebookAccount = $facebookAccount;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getInstagramAccount(): ?string
    {
        return $this->instagramAccount;
    }

    /**
     * @param string|null $instagramAccount
     * @return $this
     */
    public function setInstagramAccount(?string $instagramAccount): self
    {
        $this->instagramAccount = $instagramAccount;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getTwitterAccount(): ?string
    {
        return $this->twitterAccount;
    }

    /**
     * @param string|null $twitterAccount
     * @return $this
     */
    public function setTwitterAccount(?string $twitterAccount): self
    {
        $this->twitterAccount = $twitterAccount;

        return $this;
    }

    /**
     * @return bool|null
     */
    public function getMentorAccept(): ?bool
    {
        return $this->mentorAccept;
    }

    /**
     * @param bool|null $mentorAccept
     * @return $this
     */
    public function setMentorAccept(?bool $mentorAccept): self
    {
        $this->mentorAccept = $mentorAccept;

        return $this;
    }





    /**
     * @return Collection<int, GroupMember>
     */
    public function getGroupsMemberOf(): Collection
    {
        return $this->groupsMemberOf;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return Collection<int, Conversation>
     */
    public function getConversations(): Collection
    {
        return $this->conversations;
    }

    public function addConversation(Conversation $conversation): self
    {
        if (!$this->conversations->contains($conversation)) {
            $this->conversations[] = $conversation;
            $conversation->addUser($this);
        }

        return $this;
    }

    public function removeConversation(Conversation $conversation): self
    {
        if ($this->conversations->removeElement($conversation)) {
            $conversation->removeUser($this);
        }

        return $this;
    }



}
