<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use App\Controller\User\UpdateProfilAction;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use App\Controller\User\CharteDutilisationAction;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;  
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

/**
 *
 */
#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ApiResource(itemOperations: [
    'get','put','delete', 'patch',
    'charte_user' => [  
        'method' => 'POST',  
        'path' => '/charteAction/{id}',     
        'controller' => CharteDutilisationAction::class,
    ], 
    'updateProfil_user' => [  
        'method' => 'PUT',  
        'path' => '/updateProfil/user/{id}',     
        'controller' => UpdateProfilAction::class,  
    ],   
]
)]
class User implements UserInterface
{
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


    public function __construct()
    {
        $this->notifications = new ArrayCollection();
        $this->savedOfferSearches = new ArrayCollection();
        $this->emailNotifications = new ArrayCollection();
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


}
