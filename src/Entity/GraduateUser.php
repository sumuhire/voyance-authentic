<?php

namespace App\Entity;


use App\Entity\School;
use App\Entity\Issuance;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Table(name="graduate_user")
 * @ORM\Entity(repositoryClass="App\Repository\GraduateUserRepository")
 */
class GraduateUser
{
 
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="UUID")
     * @ORM\Column(type="string", length=36)
     * @Groups({"public"})
     */
    public $id;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\School", inversedBy="graduateUsers")
     */
    private $school;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Issuance", mappedBy="graduateUsers")
     */
    private $issuances;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Faculty", mappedBy="graduateUsers")
     */
    private $faculties;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\User", mappedBy="graduateUser", cascade={"persist", "remove"})
     */
    private $user;

    /**
     * @ORM\Column(type="datetime")
     */
    private $creationDate;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Degree", mappedBy="graduateUser", orphanRemoval=true)
     */
    private $degrees;

    public function __construct()
    {
        $this->creationDate = new \DateTime();
        $this->school = new ArrayCollection();
        $this->issuances = new ArrayCollection();
        $this->roles = new ArrayCollection();
        $this->faculties = new ArrayCollection();
        $this->degrees = new ArrayCollection();
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * @return Collection|School[]
     */
    public function getSchool(): Collection
    {
        return $this->school;
    }

    public function addSchool(School $school): self
    {
        if (!$this->school->contains($school)) {
            $this->school[] = $school;
        }

        return $this;
    }

    public function removeSchool(School $school): self
    {
        if ($this->school->contains($school)) {
            $this->school->removeElement($school);
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
            $issuance->addGraduateUser($this);
        }

        return $this;
    }

    public function removeIssuance(Issuance $issuance): self
    {
        if ($this->issuances->contains($issuance)) {
            $this->issuances->removeElement($issuance);
            $issuance->removeGraduateUser($this);
        }

        return $this;
    }

    /**
     * @return Collection|Faculty[]
     */
    public function getFaculties(): Collection
    {
        return $this->faculties;
    }

    public function addFaculty(Faculty $faculty): self
    {
        if (!$this->faculties->contains($faculty)) {
            $this->faculties[] = $faculty;
            $faculty->addGraduateUser($this);
        }

        return $this;
    }

    public function removeFaculty(Faculty $faculty): self
    {
        if ($this->faculties->contains($faculty)) {
            $this->faculties->removeElement($faculty);
            $faculty->removeGraduateUser($this);
        }

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        // set (or unset) the owning side of the relation if necessary
        $newGraduateUser = $user === null ? null : $this;
        if ($newGraduateUser !== $user->getGraduateUser()) {
            $user->setGraduateUser($newGraduateUser);
        }

        return $this;
    }

    public function getCreationDate(): ?\DateTimeInterface
    {
        return $this->creationDate;
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
            $degree->setGraduateUser($this);
        }

        return $this;
    }

    public function removeDegree(Degree $degree): self
    {
        if ($this->degrees->contains($degree)) {
            $this->degrees->removeElement($degree);
            // set the owning side to null (unless already changed)
            if ($degree->getGraduateUser() === $this) {
                $degree->setGraduateUser(null);
            }
        }

        return $this;
    }

}
