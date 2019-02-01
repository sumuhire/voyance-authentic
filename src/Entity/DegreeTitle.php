<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DegreeTitleRepository")
 */
class DegreeTitle
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="UUID")
     * @ORM\Column(type="string", length=36)
     * @Groups({"public"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $label;

    /**
     * @ORM\Column(type="integer")
     */
    private $ects;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Issuance", mappedBy="degreeTitle")
     */
    private $issuances;

    /**
     * @ORM\Column(type="string", length=25)
     */
    private $degreeCode;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\School", inversedBy="degreeTitles", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $school;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Degree", mappedBy="degreeTitle")
     */
    private $degrees;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $url;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\DegreeField", inversedBy="degreeTitles")
     * @ORM\JoinColumn(nullable=false)
     */
    private $degreeField;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Faculty", inversedBy="degreeTitles")
     * @ORM\JoinColumn(nullable=false)
     */
    private $faculty;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\DegreeType", inversedBy="degreeTitles")
     * @ORM\JoinColumn(nullable=false)
     */
    private $degreeType;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\StudyTypeTitles", inversedBy="degreeTitles", cascade={"persist"})
     */
    private $studyTypeTitles;

    public function __construct()
    {
        $this->issuances = new ArrayCollection();
        $this->degrees = new ArrayCollection();
        $this->studyTypeTitles = new ArrayCollection();
       
    }

    public function getId(): ?string
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

    public function getEcts(): ?int
    {
        return $this->ects;
    }

    public function setEcts(int $ects): self
    {
        $this->ects = $ects;

        return $this;
    }

    /**
     * @return Collection|Issuance[]
     */
    public function getIssuances(): Collection
    {
        return $this->issuances;
    }

    public function addIssuance(Issuance $issuance): self
    {
        if (!$this->issuances->contains($issuance)) {
            $this->issuances[] = $issuance;
            $issuance->setDegreeTitle($this);
        }

        return $this;
    }

    public function removeIssuance(Issuance $issuance): self
    {
        if ($this->issuances->contains($issuance)) {
            $this->issuances->removeElement($issuance);
            // set the owning side to null (unless already changed)
            if ($issuance->getDegreeTitle() === $this) {
                $issuance->setDegreeTitle(null);
            }
        }

        return $this;
    }

    public function getDegreeCode(): ?string
    {
        return $this->degreeCode;
    }

    public function setDegreeCode(string $degreeCode): self
    {
        $this->degreeCode = $degreeCode;

        return $this;
    }

    public function getSchool(): ?School
    {
        return $this->school;
    }

    public function setSchool(?School $school): self
    {
        $this->school = $school;

        return $this;
    }

    /**
     * @return Collection|Degree[]
     */
    public function getDegrees(): Collection
    {
        return $this->degrees;
    }

    public function addDegree(Degree $degree): self
    {
        if (!$this->degrees->contains($degree)) {
            $this->degrees[] = $degree;
            $degree->setDegreeTitle($this);
        }

        return $this;
    }

    public function removeDegree(Degree $degree): self
    {
        if ($this->degrees->contains($degree)) {
            $this->degrees->removeElement($degree);
            // set the owning side to null (unless already changed)
            if ($degree->getDegreeTitle() === $this) {
                $degree->setDegreeTitle(null);
            }
        }

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(?string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function getDegreeField(): ?DegreeField
    {
        return $this->degreeField;
    }

    public function setDegreeField(?DegreeField $degreeField): self
    {
        $this->degreeField = $degreeField;

        return $this;
    }

    public function getFaculty(): ?Faculty
    {
        return $this->faculty;
    }

    public function setFaculty(?Faculty $faculty): self
    {
        $this->faculty = $faculty;

        return $this;
    }

    public function getDegreeType(): ?DegreeType
    {
        return $this->degreeType;
    }

    public function setDegreeType(?DegreeType $degreeType): self
    {
        $this->degreeType = $degreeType;

        return $this;
    }

    public function getStudyTypeTitles(): ?StudyTypeTitles
    {
        return $this->studyTypeTitles;
    }

    public function setStudyTypeTitles(?StudyTypeTitles $studyTypeTitles): self
    {
        $this->studyTypeTitles = $studyTypeTitles;

        return $this;
    }

}
