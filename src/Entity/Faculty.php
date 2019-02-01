<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\FacultyRepository")
 */
class Faculty
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="UUID")
     * @ORM\Column(type="string", length=36)
     * @Groups({"public"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $label;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\DegreeField", mappedBy="faculty", orphanRemoval=true)
     */
    private $degreeField;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Issuance", mappedBy="faculty", cascade={"persist", "remove"})
     */
    private $issuances;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\School", inversedBy="faculties")
     * @ORM\JoinColumn(nullable=false)
     */
    private $school;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\GraduateUser", inversedBy="faculties")
     */
    private $graduateUsers;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\SchoolUser", inversedBy="faculties")
     */
    private $schoolUsers;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\SchoolUser", mappedBy="facultyMember")
     */
    private $schoolUserMembers;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\DegreeTitle", mappedBy="faculty")
     */
    private $degreeTitles;


    public function __construct()
    {
        $this->degreeField = new ArrayCollection();
        $this->graduateUsers = new ArrayCollection();
        $this->schoolUsers = new ArrayCollection();
        $this->issuance = new ArrayCollection();
        $this->schoolUserMembers = new ArrayCollection();
        $this->degreeTitles = new ArrayCollection();
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
            $degreeField->setFaculty($this);
        }

        return $this;
    }

    public function removeDegreeField(DegreeField $degreeField): self
    {
        if ($this->degreeField->contains($degreeField)) {
            $this->degreeField->removeElement($degreeField);
            // set the owning side to null (unless already changed)
            if ($degreeField->getFaculty() === $this) {
                $degreeField->setFaculty(null);
            }
        }

        return $this;
    }

     /**
     * @return Collection|Issuances[]
     */
    public function getIssuances(): Collection
    {
        return $this->issuances;
    }

    public function addIssuance(Issuance $issuance): self
    {
        if (!$this->issuances->contains($issuance)) {
            $this->issuances[] = $issuance;
            $issuances->setFaculty($this);
        }

        return $this;
    }

    public function removeIssuance(Issuance $issuance): self
    {
        if ($this->issuances->contains($issuances)) {
            $this->issuances->removeElement($issuance);
            // set the owning side to null (unless already changed)
            if ($issuances->getFaculty() === $this) {
                $issuances->setFaculty(null);
            }
        }

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
     * @return Collection|GraduateUser[]
     */
    public function getGraduateUsers(): Collection
    {
        return $this->graduateUsers;
    }

    public function addGraduateUser(GraduateUser $graduateUser): self
    {
        if (!$this->graduateUsers->contains($graduateUser)) {
            $this->graduateUsers[] = $graduateUser;
        }

        return $this;
    }

    public function removeGraduateUser(GraduateUser $graduateUser): self
    {
        if ($this->graduateUsers->contains($graduateUser)) {
            $this->graduateUsers->removeElement($graduateUser);
        }

        return $this;
    }

    /**
     * @return Collection|SchoolUser[]
     */
    public function getSchoolUsers(): Collection
    {
        return $this->schoolUsers;
    }

    public function addSchoolUser(SchoolUser $schoolUser): self
    {
        if (!$this->schoolUsers->contains($schoolUser)) {
            $this->schoolUsers[] = $schoolUser;
        }

        return $this;
    }

    public function removeSchoolUser(SchoolUser $schoolUser): self
    {
        if ($this->schoolUsers->contains($schoolUser)) {
            $this->schoolUsers->removeElement($schoolUser);
        }

        return $this;
    }

    /**
     * @return Collection|SchoolUser[]
     */
    public function getSchoolUserMembers(): Collection
    {
        return $this->schoolUserMembers;
    }

    public function addSchoolUserMember(SchoolUser $schoolUserMember): self
    {
        if (!$this->schoolUserMembers->contains($schoolUserMember)) {
            $this->schoolUserMembers[] = $schoolUserMember;
            $schoolUserMember->setFacultyMember($this);
        }

        return $this;
    }

    public function removeSchoolUserMember(SchoolUser $schoolUserMember): self
    {
        if ($this->schoolUserMembers->contains($schoolUserMember)) {
            $this->schoolUserMembers->removeElement($schoolUserMember);
            // set the owning side to null (unless already changed)
            if ($schoolUserMember->getFacultyMember() === $this) {
                $schoolUserMember->setFacultyMember(null);
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
            $degreeTitle->setFaculty($this);
        }

        return $this;
    }

    public function removeDegreeTitle(DegreeTitle $degreeTitle): self
    {
        if ($this->degreeTitles->contains($degreeTitle)) {
            $this->degreeTitles->removeElement($degreeTitle);
            // set the owning side to null (unless already changed)
            if ($degreeTitle->getFaculty() === $this) {
                $degreeTitle->setFaculty(null);
            }
        }

        return $this;
    }

}
