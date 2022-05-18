<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\PhonebookRepository;
use ApiPlatform\Core\Annotation\ApiResource;

#[ORM\Entity(repositoryClass: PhonebookRepository::class)]
#[ApiResource]    
class Phonebook
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $user_type;

    #[ORM\Column(type: 'boolean')]
    private $isHidden;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserType(): ?string
    {
        return $this->user_type;
    }

    public function setUserType(string $user_type): self
    {
        $this->user_type = $user_type;

        return $this;
    }

    public function getIsHidden(): ?bool
    {
        return $this->isHidden;
    }

    public function setIsHidden(bool $isHidden): self
    {
        $this->isHidden = $isHidden;

        return $this;
    }
}
