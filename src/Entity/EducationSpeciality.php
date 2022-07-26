<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use App\Repository\EducationSpecialityRepository;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: EducationSpecialityRepository::class)]
#[ApiResource]
class EducationSpeciality
{
    const SPECIALITY1 = "Spécialité 1";
    const SPECIALITY2 = "Spécialité 2";
    const SPECIALITY3 = "Spécialité 3";
    const SPECIALITY4 = "Spécialité 4";

    /**
     * @var int
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    /**
     * @var string
     */
    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['group:read'])]
    private string $label;

    /**
     * @var Collection<int, Group>
     */
    #[ORM\OneToMany(mappedBy: 'educationSpeciality', targetEntity: Group::class)]
    private Collection $groups;

    public function __construct()
    {
        $this->groups = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $label): self
    {
        $this->label = $label;

        return $this;
    }

    /**
     * @return Collection<int, Group>
     */
    public function getGroups(): Collection
    {
        return $this->groups;
    }

    public function addGroup(Group $group): self
    {
        if (!$this->groups->contains($group)) {
            $this->groups[] = $group;
            $group->setEducationSpeciality($this);
        }

        return $this;
    }

    public function removeGroup(Group $group): self
    {
        if ($this->groups->removeElement($group)) {
            // set the owning side to null (unless already changed)
            if ($group->getEducationSpeciality() === $this) {
                $group->setEducationSpeciality(null);
            }
        }

        return $this;
    }
}
