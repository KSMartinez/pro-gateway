<?php

namespace App\Entity;


use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ExperienceRepository;
use ApiPlatform\Core\Annotation\ApiResource;   
use App\Controller\Experience\CheckExperienceDatasAction;

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
    private $id;


     /**
     * @var string|null
     */
    #[ORM\Column(type: 'text', nullable: true)]
    private $description;

     /**
     * @var string  
     */
    #[ORM\Column(type: 'string', length: 255)]
    private $jobname;

     /**
     * @var string|null
     */
    #[ORM\Column(type: 'string', length: 255, nullable: true)]

    private $company;

     /**
     * @var string|null
     */
    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $category;

     /**
     * @var string|null
     */
    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $country;

     /**
     * @var string|null
     */
    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $city;


    
      /**  
     * @var DateTimeInterface|null
     */
    #[ORM\Column(type: 'date', nullable: true)]
    private $startMonth;

    
      /**  
     * @var DateTimeInterface|null
     */
    #[ORM\Column(type: 'date', nullable: true)]
    private $startYear;


    
      /**  
     * @var DateTimeInterface|null
     */
    #[ORM\Column(type: 'date', nullable: true)]
    private $endMonth;


    
      /**  
     * @var DateTimeInterface|null
     */
    #[ORM\Column(type: 'date', nullable: true)]
    private $endYear;

    
      /**  
     * @var string|null  
     */
    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $current_job;   
  

    
      /**  
     * @var CV|null
     */
    #[ORM\ManyToOne(targetEntity: CV::class, inversedBy: 'experiences')]
    private $cv;
  
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

    public function getJobname(): ?string
    {
        return $this->jobname;
    }

    public function setJobname(string $jobname): self
    {
        $this->jobname = $jobname;

        return $this;
    }

    public function getCompany(): ?string
    {
        return $this->company;
    }

    public function setCompany(?string $company): self
    {
        $this->company = $company;

        return $this;
    }

    public function getCategory(): ?string
    {
        return $this->category;
    }

    public function setCategory(?string $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(?string $country): self
    {
        $this->country = $country;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(?string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getStartMonth(): ?\DateTimeInterface
    {
        return $this->startMonth;
    }

    public function setStartMonth(?\DateTimeInterface $startMonth): self
    {
        $this->startMonth = $startMonth;

        return $this;
    }

    public function getStartYear(): ?\DateTimeInterface
    {
        return $this->startYear;
    }

    public function setStartYear(?\DateTimeInterface $startYear): self
    {
        $this->startYear = $startYear;

        return $this;
    }

    public function getEndMonth(): ?\DateTimeInterface
    {
        return $this->endMonth;
    }

    public function setEndMonth(?\DateTimeInterface $endMonth): self
    {
        $this->endMonth = $endMonth;

        return $this;
    }

    public function getEndYear(): ?\DateTimeInterface
    {
        return $this->endYear;
    }

    public function setEndYear(?\DateTimeInterface $endYear): self
    {
        $this->endYear = $endYear;

        return $this;
    }

    public function getCurrentJob(): ?string
    {
        return $this->current_job;
    }

    public function setCurrentJob(?string $current_job): self
    {
        $this->current_job = $current_job;

        return $this;
    }

    public function getCV(): ?CV
    {    
        return $this->cv;
    }

    public function setCV(?CV $cv): self   
    {
        $this->cv = $cv;

        return $this;
    }



   
  
}
