<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DegreeTypeRepository")
 */
class DegreeType
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="UUID")
     * @ORM\Column(type="string", length=36)
     * @Groups({"public"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $label;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\DegreeTitle", mappedBy="degreeType")
     */
    private $degreeTitles;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\DegreeField", inversedBy="degreeTypes")
     */
    private $degreeField;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\StudyTypeTitles", mappedBy="degreeType")
     */
    private $studyTypeTitles;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Issuance", mappedBy="degreeType")
     */
    private $issuances;


    public function __construct()
    {
        $this->degreeTitles = new ArrayCollection();
        $this->degreeField = new ArrayCollection();
        $this->studyTypeTitles = new ArrayCollection();
        $this->issuances = new ArrayCollection();
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

    /**
     * @return Collection|DegreeTitle[]
     */
    public function getDegreeTitles(): Collection
    {
        return $this->degreeTitles;
    }

    public function addDegreeTitle(DegreeTitle $degreeTitle): self
    {
        if (!$this->degreeTitles->contains($degreeTitle)) {
            $this->degreeTitles[] = $degreeTitle;
            $degreeTitle->setDegreeType($this);
        }

        return $this;
    }

    public function removeDegreeTitle(DegreeTitle $degreeTitle): self
    {
        if ($this->degreeTitles->contains($degreeTitle)) {
            $this->degreeTitles->removeElement($degreeTitle);
            // set the owning side to null (unless already changed)
            if ($degreeTitle->getDegreeType() === $this) {
                $degreeTitle->setDegreeType(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|DegreeField[]
     */
    public function getDegreeField(): Collection
    {
        return $this->degreeField;
    }

    public function addDegreeField(DegreeField $degreeField): self
    {
        if (!$this->degreeField->contains($degreeField)) {
            $this->degreeField[] = $degreeField;
        }

        return $this;
    }

    public function removeDegreeField(DegreeField $degreeField): self
    {
        if ($this->degreeField->contains($degreeField)) {
            $this->degreeField->removeElement($degreeField);
        }

        return $this;
    }



    /**
     * @return Collection|StudyTypeTitles[]
     */
    public function getStudyTypeTitles(): Collection
    {
        return $this->studyTypeTitles;
    }

    public function addStudyTypeTitles(StudyTypeTitles $studyTypeTitles): self
    {
        if (!$this->studyTypeTitles->contains($studyTypeTitles)) {
            $this->studyTypeTitles[] = $studyTypeTitles;
            $studyTypeTitles->setDegreeType($this);
        }

        return $this;
    }

    public function removeStudyTypeTitles(StudyTypeTitles $studyTypeTitles): self
    {
        if ($this->studyTypeTitles->contains($studyTypeTitles)) {
            $this->studyTypeTitles->removeElement($studyTypeTitles);
            // set the owning side to null (unless already changed)
            if ($studyTypeTitles->getDegreeType() === $this) {
                $studyTypeTitles->setDegreeType(null);
            }
        }

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
            $issuance->setDegreeType($this);
        }

        return $this;
    }

    public function removeIssuance(Issuance $issuance): self
    {
        if ($this->issuances->contains($issuance)) {
            $this->issuances->removeElement($issuance);
            // set the owning side to null (unless already changed)
            if ($issuance->getDegreeType() === $this) {
                $issuance->setDegreeType(null);
            }
        }

        return $this;
    }

}
