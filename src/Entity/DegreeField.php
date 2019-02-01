<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DegreeFieldRepository")
 */
class DegreeField
{
   /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="UUID")
     * @ORM\Column(type="string", length=36)
     * @Groups({"public"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $label;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Faculty", inversedBy="degreeField")
     * @ORM\JoinColumn(nullable=false)
     */
    private $faculty;
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\SchoolUser", inversedBy="degreeFieldsCreated")
     * @ORM\JoinColumn(nullable=false)
     */
    private $author;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Issuance", mappedBy="degreeField", cascade={"persist", "remove"})
     */
    private $issuances;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\DegreeTitle", mappedBy="degreeField")
     */
    private $degreeTitles;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\DegreeType", mappedBy="degreeField")
     */
    private $degreeTypes;

    public function __construct()
    {
        $this->issuances = new ArrayCollection();
        $this->degreeTitles = new ArrayCollection();
        $this->degreeTypes = new ArrayCollection();
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

    public function getFaculty(): ?Faculty
    {
        return $this->faculty;
    }

    public function setFaculty(?Faculty $faculty): self
    {
        $this->faculty = $faculty;

        return $this;
    }

    public function getIssuance(): ?Issuance
    {
        return $this->issuance;
    }

    public function setIssuance(Issuance $issuance): self
    {
        $this->issuance = $issuance;

        // set the owning side of the relation if necessary
        if ($this !== $issuance->getDegreeField()) {
            $issuance->setDegreeField($this);
        }

        return $this;
    }

    public function getAuthor(): ?SchoolUser
    {
        return $this->author;
    }

    public function setAuthor(?SchoolUser $author): self
    {
        $this->author = $author;

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
            $issuance->setDegreeField($this);
        }

        return $this;
    }

    public function removeIssuance(Issuance $issuance): self
    {
        if ($this->issuances->contains($issuance)) {
            $this->issuances->removeElement($issuance);
            // set the owning side to null (unless already changed)
            if ($issuance->getDegreeField() === $this) {
                $issuance->setDegreeField(null);
            }
        }

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
            $degreeTitle->setDegreeField($this);
        }

        return $this;
    }

    public function removeDegreeTitle(DegreeTitle $degreeTitle): self
    {
        if ($this->degreeTitles->contains($degreeTitle)) {
            $this->degreeTitles->removeElement($degreeTitle);
            // set the owning side to null (unless already changed)
            if ($degreeTitle->getDegreeField() === $this) {
                $degreeTitle->setDegreeField(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|DegreeType[]
     */
    public function getDegreeTypes(): Collection
    {
        return $this->degreeTypes;
    }

    public function addDegreeType(DegreeType $degreeType): self
    {
        if (!$this->degreeTypes->contains($degreeType)) {
            $this->degreeTypes[] = $degreeType;
            $degreeType->addDegreeField($this);
        }

        return $this;
    }

    public function removeDegreeType(DegreeType $degreeType): self
    {
        if ($this->degreeTypes->contains($degreeType)) {
            $this->degreeTypes->removeElement($degreeType);
            $degreeType->removeDegreeField($this);
        }

        return $this;
    }
}
