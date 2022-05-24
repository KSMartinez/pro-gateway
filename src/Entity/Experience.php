<?php

namespace App\Entity;


use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ExperienceRepository;
use ApiPlatform\Core\Annotation\ApiResource;   
use App\Controller\Experience\CheckExperienceDatasAction;

/**
 *
 */
#[ORM\Entity(repositoryClass: ExperienceRepository::class)]
#[ApiResource(
    itemOperations  : [
        'get','put','delete',
        'checkExperienceDatas' => [  
            'method' => 'GET',    
            'path' => '/checkExperienceDatas/{id}',     
            'controller' => CheckExperienceDatasAction::class,   
        ],   
    ],     
)]  
class Experience
{

      /**  
     * @var int|null
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;


     /**
     * @var string|null
     */
    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $description;

     /**
     * @var string  
     */
    #[ORM\Column(type: 'string', length: 255)]
    private string $jobname;

     /**
     * @var string|null
     */
    #[ORM\Column(type: 'string', length: 255, nullable: true)]

    private ?string $company;

     /**
     * @var string|null
     */
    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $category;

     /**
     * @var string|null
     */
    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $country;

     /**
     * @var string|null
     */
    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $city;


    
      /**  
     * @var DateTimeInterface|null
     */
    #[ORM\Column(type: 'date', nullable: true)]
    private ?DateTimeInterface $startMonth;

    
      /**  
     * @var DateTimeInterface|null
     */
    #[ORM\Column(type: 'date', nullable: true)]
    private ?DateTimeInterface $startYear;


    
      /**  
     * @var DateTimeInterface|null
     */
    #[ORM\Column(type: 'date', nullable: true)]
    private ?DateTimeInterface $endMonth;


    
      /**  
     * @var DateTimeInterface|null
     */
    #[ORM\Column(type: 'date', nullable: true)]
    private ?DateTimeInterface $endYear;

    
      /**  
     * @var string|null  
     */
    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $current_job;
  

    
      /**  
     * @var CV|null
     */
    #[ORM\ManyToOne(targetEntity: CV::class, inversedBy: 'experiences')]
    private ?CV $cv;

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
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string|null $description
     * @return $this
     */
    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getJobname(): ?string
    {
        return $this->jobname;
    }

    /**
     * @param string $jobname
     * @return $this
     */
    public function setJobname(string $jobname): self
    {
        $this->jobname = $jobname;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getCompany(): ?string
    {
        return $this->company;
    }

    /**
     * @param string|null $company
     * @return $this
     */
    public function setCompany(?string $company): self
    {
        $this->company = $company;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getCategory(): ?string
    {
        return $this->category;
    }

    /**
     * @param string|null $category
     * @return $this
     */
    public function setCategory(?string $category): self
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getCountry(): ?string
    {
        return $this->country;
    }

    /**
     * @param string|null $country
     * @return $this
     */
    public function setCountry(?string $country): self
    {
        $this->country = $country;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getCity(): ?string
    {
        return $this->city;
    }

    /**
     * @param string|null $city
     * @return $this
     */
    public function setCity(?string $city): self
    {
        $this->city = $city;

        return $this;
    }

    /**
     * @return DateTimeInterface|null
     */
    public function getStartMonth(): ?\DateTimeInterface
    {
        return $this->startMonth;
    }

    /**
     * @param DateTimeInterface|null $startMonth
     * @return $this
     */
    public function setStartMonth(?\DateTimeInterface $startMonth): self
    {
        $this->startMonth = $startMonth;

        return $this;
    }

    /**
     * @return DateTimeInterface|null
     */
    public function getStartYear(): ?\DateTimeInterface
    {
        return $this->startYear;
    }

    /**
     * @param DateTimeInterface|null $startYear
     * @return $this
     */
    public function setStartYear(?\DateTimeInterface $startYear): self
    {
        $this->startYear = $startYear;

        return $this;
    }

    /**
     * @return DateTimeInterface|null
     */
    public function getEndMonth(): ?\DateTimeInterface
    {
        return $this->endMonth;
    }

    /**
     * @param DateTimeInterface|null $endMonth
     * @return $this
     */
    public function setEndMonth(?\DateTimeInterface $endMonth): self
    {
        $this->endMonth = $endMonth;

        return $this;
    }

    /**
     * @return DateTimeInterface|null
     */
    public function getEndYear(): ?\DateTimeInterface
    {
        return $this->endYear;
    }

    /**
     * @param DateTimeInterface|null $endYear
     * @return $this
     */
    public function setEndYear(?\DateTimeInterface $endYear): self
    {
        $this->endYear = $endYear;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getCurrentJob(): ?string
    {
        return $this->current_job;
    }

    /**
     * @param string|null $current_job
     * @return $this
     */
    public function setCurrentJob(?string $current_job): self
    {
        $this->current_job = $current_job;

        return $this;
    }

    /**
     * @return CV|null
     */
    public function getCV(): ?CV
    {    
        return $this->cv;
    }

    /**
     * @param CV|null $cv
     * @return $this
     */
    public function setCV(?CV $cv): self
    {
        $this->cv = $cv;

        return $this;
    }



   
  
}
