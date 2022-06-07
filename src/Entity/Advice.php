<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\AdviceRepository;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Core\Annotation\ApiFilter;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Serializer\Annotation\Groups;
/**
 * @ApiFilter(SearchFilter::class, properties={
 *      "name":"ASC",
 *      "university":"partial",
 * })
 */
#[ORM\Entity(repositoryClass: AdviceRepository::class)]
#[ApiResource]    
class Advice
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
     * @var string
     */
    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $university;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $document;

    /**
     * @Vich\UploadableField(mapping="media_object", fileNameProperty="document")
     */
    // #[Groups(['user:updatePicture'])]
    public ?File $documentFile = null;

    #[ORM\Column(type: 'boolean')]
    private bool $isPublic;


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

    /**
     * @return File|null
     */
    public function getFile(): ?File
    {
        return $this->documentFile;
    }

    /**
     * @param File|null $documentFile
     */
    public function setFile(?File $documentFile): void
    {
        $this->documentFile = $documentFile;
    }


    public function getDocument(): ?string
    {
        return $this->document;
    }

    public function setDocument(?string $document): self
    {
        $this->document = $document;

        return $this;
    }

    public function getIsPublic(): ?bool
    {
        return $this->isPublic;
    }

    public function setIsPublic(bool $isPublic): self
    {
        $this->isPublic = $isPublic;

        return $this;
    }
}
