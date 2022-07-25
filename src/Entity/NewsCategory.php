<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\NewsCategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Validator\Constraints\NewsCategory as Assert;
use Symfony\Component\Validator\Constraints as AssertVendor;

#[ORM\Entity(repositoryClass: NewsCategoryRepository::class)]
#[UniqueEntity('title')]
#[ApiResource(attributes: ["pagination_enabled" => false])]
class NewsCategory
{

    const DEFAULT_CATEGORY = 'other';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['news:read'])]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 255, unique: true, nullable: true,
        options: ['default' => self::DEFAULT_CATEGORY]
    )]
    #[Assert\TitleRequirements]
    #[Groups(['news:read', 'news:read:item', 'news:create'])]
    private ?string $label;

    /**
     * @var Collection<int, News>
     */
    #[ORM\OneToMany(mappedBy: 'category', targetEntity: News::class)]
    private Collection $news;


    public function __construct()
    {
        $this->news = new ArrayCollection();
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
     * @return Collection<int, News>
     */
    public function getNews(): Collection
    {
        return $this->news;
    }

}
