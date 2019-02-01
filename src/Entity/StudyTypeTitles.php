<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\StudyTypeTitlesRepository")
 */
class StudyTypeTitles
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="UUID")
     * @ORM\Column(type="string", length=36)
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\DegreeType", inversedBy="studyTypeTitles")
     */
    private $degreeType;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\DegreeTitle", mappedBy="studyTypeTitles", cascade={"persist"})
     */
    private $degreeTitles;

    public function __construct()
    {
        $this->degreeTitles = new ArrayCollection();
    }

    public function getId(): ?string
    {
        return $this->id;
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
            $degreeTitle->setStudyTypeTitles($this);
        }

        return $this;
    }

    public function removeDegreeTitle(DegreeTitle $degreeTitle): self
    {
        if ($this->degreeTitles->contains($degreeTitle)) {
            $this->degreeTitles->removeElement($degreeTitle);
            // set the owning side to null (unless already changed)
            if ($degreeTitle->getStudyTypeTitles() === $this) {
                $degreeTitle->setStudyTypeTitles(null);
            }
        }

        return $this;
    }
}
