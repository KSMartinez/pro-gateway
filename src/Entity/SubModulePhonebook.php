<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\SubModulePhonebookRepository;

#[ORM\Entity(repositoryClass: SubModulePhonebookRepository::class)]
#[ApiResource]     
class SubModulePhonebook
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $userType;

    #[ORM\Column(type: 'string', length: 255)]
    private $name;

    #[ORM\Column(type: 'boolean')]
    private $isHidden;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserType(): ?string
    {
        return $this->userType;
    }

    public function setUserType(string $userType): self
    {
        $this->userType = $userType;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

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
