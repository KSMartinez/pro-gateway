<?php

namespace App\Entity;



use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use App\Entity\CV;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\SkillRepository;
use ApiPlatform\Core\Annotation\ApiResource;   

#[ORM\Entity(repositoryClass: SkillRepository::class)]
#[ApiResource(
    collectionOperations: ['get', 'post'],
    itemOperations: ['get', 'put', 'delete']
)]
#[ApiFilter(SearchFilter::class, properties: ['cv' => 'exact'])]
class Skill
{

    
      /**  
     * @var int|null
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]     
    private $id;


    
      /**  
     * @var CV|null
     */
    #[ORM\ManyToOne(targetEntity: CV::class, inversedBy: 'skills')]
    private $cv;


    
      /**  
     * @var string
     */
    #[ORM\Column(type: 'string', length: 255)]
    private $skill1;


      /**  
     * @var string|null
     */
    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $skill2;


    
      /**  
     * @var string|null
     */
    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $skill3;


      /**  
     * @var string|null
     */
    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $skill4;

    
      /**  
     * @var string|null
     */
    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $skill5;

    
      /**  
     * @var string|null
     */
    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $complementarySkill;
  

    public function getId(): ?int
    {  
        return $this->id;
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

    public function getSkill1(): ?string
    {
        return $this->skill1;
    }

    public function setSkill1(string $skill1): self
    {
        $this->skill1 = $skill1;

        return $this;
    }

    public function getSkill2(): ?string
    {
        return $this->skill2;
    }

    public function setSkill2(?string $skill2): self
    {
        $this->skill2 = $skill2;

        return $this;
    }

    public function getSkill3(): ?string
    {
        return $this->skill3;
    }

    public function setSkill3(?string $skill3): self
    {
        $this->skill3 = $skill3;

        return $this;
    }

    public function getSkill4(): ?string
    {
        return $this->skill4;
    }

    public function setSkill4(?string $skill4): self
    {
        $this->skill4 = $skill4;

        return $this;
    }

    public function getSkill5(): ?string
    {
        return $this->skill5;
    }

    public function setSkill5(?string $skill5): self
    {
        $this->skill5 = $skill5;

        return $this;
    }

    public function getComplementarySkill(): ?string
    {
        return $this->complementarySkill;
    }

    public function setComplementarySkill(?string $complementarySkill): self
    {
        $this->complementarySkill = $complementarySkill;

        return $this;
    }

   
}
