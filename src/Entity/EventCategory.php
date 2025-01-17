<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\EventCategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: EventCategoryRepository::class)]
#[UniqueEntity('label')]
#[ApiResource(attributes: ["pagination_enabled" => false])]
class EventCategory
{
    const DEFAULT_CATEGORY = 'other';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['event:read'])]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 255, unique: true, nullable: true,
        options: ['default' => self::DEFAULT_CATEGORY]
    )]
    #[Groups(['event:read', 'event:read:item', 'event:create'])]
    private ?string $label;

    /**
     * @var Collection<int, Event>
     */
    #[ORM\OneToMany(mappedBy: 'category', targetEntity: Event::class)]
    private Collection $events;

    public function __construct()
    {
        $this->events = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    /**
     * @param string|null $label
     * @return EventCategory
     */
    public function setLabel(?string $label): self
    {
        $this->label = $label;
        if($label === null) {
            $this->label = self::DEFAULT_CATEGORY;
        }

        return $this;
    }

    /**
     * @return Collection<int, Event>
     */
    public function getEvents(): Collection
    {
        return $this->events;
    }

    /**
     * @param Collection<int, Event> $events
     */
    public function setEvents(Collection $events): void
    {
        $this->events = $events;
    }
}
