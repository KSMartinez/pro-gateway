<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\NewsRepository;
use ApiPlatform\Core\Annotation\ApiResource;

#[ORM\Entity(repositoryClass: NewsRepository::class)]
#[ApiResource]    
class News
{  

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
    #[ORM\Column(type: 'string', length: 255)]
    private string $name;


     /**
     * @var string    
     */
    #[ORM\Column(type: 'text')]
    private string $description;


     /**
     * @var boolean     
     */
    #[ORM\Column(type: 'boolean')]
    private bool $forAllUniversities;


     /**
     * @var string|null  
     */
    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $university;   

    public function getId(): ?int
    {
        return $this->id;
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getForAllUniversities(): ?bool
    {
        return $this->forAllUniversities;
    }

    public function setForAllUniversities(bool $forAllUniversities): self
    {
        $this->forAllUniversities = $forAllUniversities;

        return $this;
    }

    public function getUniversity(): ?string
    {
        return $this->university;
    }

    public function setUniversity(?string $university): self
    {
        $this->university = $university;

        return $this;
    }
}
