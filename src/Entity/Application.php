<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ApplicationRepository;
use ApiPlatform\Core\Annotation\ApiResource;    
use App\Controller\Application\UserApplicationAction; 


#[ORM\Entity(repositoryClass: ApplicationRepository::class)]
#[ApiResource(
    collectionOperations: [
        'get',
        'post',    
        'userApplication' => [  
            'method' => 'POST',  
            'path' => '/userApplication',     
            'controller' => UserApplicationAction::class,     
        ],    
    ],   
)]       
class Application     
{

     /**
     * @var int|null
     */   
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id;


     /**
     * @var string|null
     */   
    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $description;

     /**
     * @var User 
     */
    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'applications')]
    #[ORM\JoinColumn(nullable: false)]
    private User $user;


    
     /**
     * @var Offer 
     */
    #[ORM\ManyToOne(targetEntity: Offer::class, inversedBy: 'applications')]
    #[ORM\JoinColumn(nullable: false)]
    private Offer $offer;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;
  
        return $this;
    }

    public function getOffer(): ?Offer
    {
        return $this->offer;
    }

    public function setOffer(Offer $offer): self
    {
        $this->offer = $offer;

        return $this;
    }
}
