<?php

namespace App\Entity;


use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use App\Controller\Education\CheckDatasAction;
use App\Repository\EducationRepository;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EducationRepository::class)]
#[ApiResource(
    itemOperations: [
        'get', 'put', 'delete',
        'checkDatas' => [
            'method' => 'GET',
            'path' => '/checkDatas/{id}',
            'controller' => CheckDatasAction::class,
        ],
    ],

)]
#[ApiFilter(SearchFilter::class, properties: ['cv' => 'exact'])]
class Education
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
    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $diploma;

     /**
     * @var string|null 
     */
    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $studyLevel;
   
     /**
     * @var string|null 
     */
    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $domain;

     /**
     * @var string|null
     */
    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $school;


      /**
     * @var DateTimeInterface|null
     */
    #[ORM\Column(type: 'date', nullable: true)]
    private ?DateTimeInterface $startMonth;

      /**
     * @var DateTimeInterface|null
     */
    #[ORM\Column(type: 'date', nullable: true)]
    private ?DateTimeInterface $endMonth;

      /**
     * @var DateTimeInterface|null
     */
    #[ORM\Column(type: 'date', nullable: true)]
    private ?DateTimeInterface $startYear;

      /**  
     * @var DateTimeInterface|null 
     */
    #[ORM\Column(type: 'date', nullable: true)]
    private ?DateTimeInterface $endYear;

      /**
     * @var CV|null
     */
    #[ORM\ManyToOne(targetEntity: CV::class, inversedBy: 'educations')]
    private ?CV $cv;


     /**
     * @var string
     */
    #[ORM\Column(type: 'string', length: 255)]
    private string $currentSchool;
  

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDiploma(): ?string
    {
        return $this->diploma;
    }

    public function setDiploma(?string $diploma): self
    {
        $this->diploma = $diploma;

        return $this;
    }

    public function getStudyLevel(): ?string
    {
        return $this->studyLevel;
    }

    public function setStudyLevel(?string $studyLevel): self
    {
        $this->studyLevel = $studyLevel;

        return $this;
    }

    public function getDomain(): ?string
    {
        return $this->domain;
    }

    public function setDomain(?string $domain): self
    {
        $this->domain = $domain;

        return $this;
    }

    public function getSchool(): ?string
    {
        return $this->school;
    }

    public function setSchool(?string $school): self
    {
        $this->school = $school;

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

    public function getEndMonth(): ?\DateTimeInterface
    {
        return $this->endMonth;
    }

    public function setEndMonth(?\DateTimeInterface $endMonth): self
    {
        $this->endMonth = $endMonth;

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

    public function getEndYear(): ?\DateTimeInterface
    {
        return $this->endYear;
    }

    public function setEndYear(?\DateTimeInterface $endYear): self
    {
        $this->endYear = $endYear;

        return $this;
    }

    public function getCv(): ?CV
    {
        return $this->cv;
    }

    public function setCv(?CV $cv): self
    {
        $this->cv = $cv;

        return $this;
    }

    public function getCurrentSchool(): ?string
    {
        return $this->currentSchool;
    }

    public function setCurrentSchool(string $currentSchool): self
    {
        $this->currentSchool = $currentSchool;

        return $this;
    }

}
