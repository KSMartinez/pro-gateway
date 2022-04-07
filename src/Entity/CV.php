<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\CVRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 *
 */
#[ORM\Entity(repositoryClass: CVRepository::class)]
#[ApiResource(
    shortName: "cvs",
    normalizationContext: [
        "groups" => [
            "cv:read"
        ]
    ],
)]
class CV
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
    #[Groups(["cv:read"])]
    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $experience;

    /**
     * @var string|null
     */
    #[Groups(["cv:read"])]
    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $education;

    /**
     * @var string|null
     */
    #[Groups(["cv:read"])]
    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $skills;

    /**
     * @var string|null
     */
    #[Groups(["cv:read"])]
    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $fileLink;


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
    public function getExperience(): ?string
    {
        return $this->experience;
    }

    /**
     * @param string|null $experience
     * @return $this
     */
    public function setExperience(?string $experience): self
    {
        $this->experience = $experience;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getEducation(): ?string
    {
        return $this->education;
    }

    /**
     * @param string|null $education
     * @return $this
     */
    public function setEducation(?string $education): self
    {
        $this->education = $education;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getSkills(): ?string
    {
        return $this->skills;
    }

    /**
     * @param string|null $skills
     * @return $this
     */
    public function setSkills(?string $skills): self
    {
        $this->skills = $skills;
        return $this;
    }

    public function getFileLink(): ?string
    {
        return $this->fileLink;
    }

    public function setFileLink(?string $fileLink): self
    {
        $this->fileLink = $fileLink;

        return $this;
    }
}
