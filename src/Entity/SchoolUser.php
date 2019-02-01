<?php

namespace App\Entity;

use App\Entity\Role;
use App\Entity\User;
use App\Entity\School;
use App\Entity\Issuance;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\EquatableInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

/**
 * @ORM\Table(name="school_user")
 * @ORM\Entity(repositoryClass="App\Repository\SchoolUserRepository")
 */
class SchoolUser
{
     /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="UUID")
     * @ORM\Column(type="string", length=36)
     * @Groups({"public"})
     */
    public $id;
    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Issuance", mappedBy="author", orphanRemoval=true)
     */
    private $issuancesCreated;

    /**
     * @ORM\Column(type="string", length=30)
     */
    private $schoolPosition;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Faculty", mappedBy="schoolUsers")
     */
    private $faculties;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\DegreeField", mappedBy="author")
     */
    private $degreeFieldsCreated;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\User", mappedBy="schoolUser", cascade={"persist", "remove"})
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\School", inversedBy="schoolUsers")
     * @ORM\JoinColumn(nullable=true)
     */
    private $school;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Invite", mappedBy="schoolUser", cascade={"persist", "remove"})
     */
    private $invite;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Faculty", inversedBy="schoolUserMembers")
     */
    private $facultyMember;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Upload", mappedBy="author")
     */
    private $uploads;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\SmartUpload", mappedBy="author")
     */
    private $smartUploads;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Degree", mappedBy="author")
     */
    private $degreesIssued;


    public function __construct()
    {

        $this->issuancesCreated = new ArrayCollection();
        $this->faculties = new ArrayCollection();
        $this->degreeFieldsCreated = new ArrayCollection();
        $this->uploads = new ArrayCollection();
        $this->degreesIssued = new ArrayCollection();
    }

    public function getId(): ?string
    {
        return $this->id;
    }
    /**
     * @return Collection|Issuance[]
     */
    public function getIssuancesCreated(): Collection
    {
        return $this->issuancesCreated;
    }

    public function addIssuancesCreated(Issuance $issuancesCreated): self
    {
        if (!$this->issuancesCreated->contains($issuancesCreated)) {
            $this->issuancesCreated[] = $issuancesCreated;
            $issuancesCreated->setAuthor($this);
        }

        return $this;
    }

    public function removeIssuancesCreated(Issuance $issuancesCreated): self
    {
        if ($this->issuancesCreated->contains($issuancesCreated)) {
            $this->issuancesCreated->removeElement($issuancesCreated);
            // set the owning side to null (unless already changed)
            if ($issuancesCreated->getAuthor() === $this) {
                $issuancesCreated->setAuthor(null);
            }
        }

        return $this;
    }

    
    // public function isEqualTo(UserInterface $user)
    // {
    //     if (!$user instanceof SchoolUser) {
    //         return false;
    //     }

    //     if ($this->password !== $user->getPassword()) {
    //         return false;
    //     }

    //     if ($this->salt !== $user->getSalt()) {
    //         return false;
    //     }

    //     if ($this->username !== $user->getUsername()) {
    //         return false;
    //     }

    //     return true;
    // }

    /** @see \Serializable::serialize() */
    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->username,
            $this->password,
            // see section on salt below
            // $this->salt,
        ));
    }

    /** @see \Serializable::unserialize() */
    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->username,
            $this->password,
            // see section on salt below
            // $this->salt
        ) = unserialize($serialized, array('allowed_classes' => false));
    }

    public function getSchoolPosition(): ?string
    {
        return $this->schoolPosition;
    }

    public function setSchoolPosition(string $schoolPosition): self
    {
        $this->schoolPosition = $schoolPosition;

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
            $faculty->addSchoolUser($this);
        }

        return $this;
    }

    public function removeFaculty(Faculty $faculty): self
    {
        if ($this->faculties->contains($faculty)) {
            $this->faculties->removeElement($faculty);
            $faculty->removeSchoolUser($this);
        }

        return $this;
    }

    /**
     * @return Collection|DegreeField[]
     */
    public function getDegreeFieldsCreated(): Collection
    {
        return $this->degreeFieldsCreated;
    }

    public function addDegreeFieldsCreated(DegreeField $degreeFieldsCreated): self
    {
        if (!$this->degreeFieldsCreated->contains($degreeFieldsCreated)) {
            $this->degreeFieldsCreated[] = $degreeFieldsCreated;
            $degreeFieldsCreated->setAuthor($this);
        }

        return $this;
    }

    public function removeDegreeFieldsCreated(DegreeField $degreeFieldsCreated): self
    {
        if ($this->degreeFieldsCreated->contains($degreeFieldsCreated)) {
            $this->degreeFieldsCreated->removeElement($degreeFieldsCreated);
            // set the owning side to null (unless already changed)
            if ($degreeFieldsCreated->getAuthor() === $this) {
                $degreeFieldsCreated->setAuthor(null);
            }
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
        $newSchoolUser = $user === null ? null : $this;
        if ($newSchoolUser !== $user->getSchoolUser()) {
            $user->setSchoolUser($newSchoolUser);
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

    public function getInvite(): ?Invite
    {
        return $this->invite;
    }

    public function setInvite(Invite $invite): self
    {
        $this->invite = $invite;

        // set the owning side of the relation if necessary
        if ($this !== $invite->getSchoolUser()) {
            $invite->setSchoolUser($this);
        }

        return $this;
    }

    public function getFacultyMember(): ?Faculty
    {
        return $this->facultyMember;
    }

    public function setFacultyMember(?Faculty $facultyMember): self
    {
        $this->facultyMember = $facultyMember;

        return $this;
    }

    /**
     * @return Collection|Upload[]
     */
    public function getUploads(): Collection
    {
        return $this->uploads;
    }

    public function addUpload(Upload $upload): self
    {
        if (!$this->uploads->contains($upload)) {
            $this->uploads[] = $upload;
            $upload->setAuthor($this);
        }

        return $this;
    }

    public function removeUpload(Upload $upload): self
    {
        if ($this->uploads->contains($upload)) {
            $this->uploads->removeElement($upload);
            // set the owning side to null (unless already changed)
            if ($upload->getAuthor() === $this) {
                $upload->setAuthor(null);
            }
        }

        return $this;
    }

     /**
     * @return Collection|SmartUpload[]
     */
    public function getSmartUploads(): Collection
    {
        return $this->smartUploads;
    }

    public function addSmartUpload(SmartUpload $smartUpload): self
    {
        if (!$this->smartUploads->contains($smartUpload)) {
            $this->smartUploads[] = $smartUpload;
            $smartUpload->setAuthor($this);
        }

        return $this;
    }

    public function removeSmartUpload(SmartUpload $smartUpload): self
    {
        if ($this->smartUploads->contains($smartUpload)) {
            $this->smartUploads->removeElement($smartUpload);
            // set the owning side to null (unless already changed)
            if ($smartUpload->getAuthor() === $this) {
                $smartUpload->setAuthor(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Degree[]
     */
    public function getDegreesIssued(): Collection
    {
        return $this->degreesIssued;
    }

    public function addDegreesIssued(Degree $degreesIssued): self
    {
        if (!$this->degreesIssued->contains($degreesIssued)) {
            $this->degreesIssued[] = $degreesIssued;
            $degreesIssued->setAuthor($this);
        }

        return $this;
    }

    public function removeDegreesIssued(Degree $degreesIssued): self
    {
        if ($this->degreesIssued->contains($degreesIssued)) {
            $this->degreesIssued->removeElement($degreesIssued);
            // set the owning side to null (unless already changed)
            if ($degreesIssued->getAuthor() === $this) {
                $degreesIssued->setAuthor(null);
            }
        }

        return $this;
    }

}
