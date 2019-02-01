<?php
namespace App\Entity;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\File;


/**
 * @ORM\Entity(repositoryClass="App\Repository\IssuanceRepository")
 */
class Issuance
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="UUID")
     * @ORM\Column(type="string", length=36)
     */
    private $id;
    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $classYearEnd;
    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $classYearStart;
    /**
     * @ORM\Column(type="datetime")
     */
    private $creationDate;
    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $editDate;
    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Invite", mappedBy="issuance")
     */
    private $invitation;
    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\GraduateUser", inversedBy="issuances")
     */
    private $graduateUsers;
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\SchoolUser", inversedBy="issuancesCreated")
     * @ORM\JoinColumn(nullable=false)
     */
    public $author;
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\School", inversedBy="issuances")
     * @ORM\JoinColumn(nullable=false)
     */
    private $school;
    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Degree", mappedBy="issuance", orphanRemoval=true, cascade={"persist"})
     */
    private $degrees;
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Faculty", inversedBy="issuances", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $faculty;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Upload", mappedBy="issuance", cascade={"persist", "remove"})
     */
    private $file;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\SmartUpload", mappedBy="issuance", cascade={"persist", "remove"})
     */
    private $smartFile;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\DegreeField", inversedBy="issuances", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank()
     */
    private $degreeField;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\GraduateUserInvite", mappedBy="issuance")
     */
    private $graduateUserInvites;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\DegreeTitle", inversedBy="issuances")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank()
     */
    private $degreeTitle;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $progress;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\DegreeType", inversedBy="issuances")
     * @ORM\JoinColumn(nullable=false)
     */
    private $degreeType;

    public function __construct()
    {
        $this->creationDate = new \DateTime();
        $this->editDate = new \DateTime();
    
        
        $this->invitation = new ArrayCollection();
        $this->graduateUsers = new ArrayCollection();
        $this->degrees = new ArrayCollection();
        $this->graduateUserInvites = new ArrayCollection();

    }
    public function getId(): ?string
    {
        return $this->id;
    }
    public function getClassYearEnd(): ?\DateTimeInterface
    {
        return $this->classYearEnd;
    }
    public function setClassYearEnd(\DateTimeInterface $classYearEnd): self
    {
        $this->classYearEnd = $classYearEnd;
        return $this;
    }
    public function getClassYearStart(): ?\DateTimeInterface
    {
        return $this->classYearStart;
    }
    public function setClassYearStart(\DateTimeInterface $classYearStart): self
    {
        $this->classYearStart = $classYearStart;
        return $this;
    }
    public function getCreationDate(): ?\DateTimeInterface
    {
        return $this->creationDate;
    }
    public function getEditDate(): ?\DateTimeInterface
    {
        return $this->editDate;
    }
    public function setEditDate(?\DateTimeInterface $editDate): self
    {
        $this->editDate = $editDate;
        return $this;
    }
    /**
     * @return Collection|Invite[]
     */
    public function getInvitation(): Collection
    {
        return $this->invitation;
    }
    public function addInvitation(Invite $invitation): self
    {
        if (!$this->invitation->contains($invitation)) {
            $this->invitation[] = $invitation;
            $invitation->setIssuance($this);
        }
        return $this;
    }
    public function removeInvitation(Invite $invitation): self
    {
        if ($this->invitation->contains($invitation)) {
            $this->invitation->removeElement($invitation);
            // set the owning side to null (unless already changed)
            if ($invitation->getIssuance() === $this) {
                $invitation->setIssuance(null);
            }
        }
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
    public function getAuthor(): ?SchoolUser
    {
        return $this->author;
    }
    public function setAuthor(?SchoolUser $author): self
    {
        $this->author = $author;
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
            $degree->setIssuance($this);
        }
        return $this;
    }
    public function removeDegree(Degree $degree): self
    {
        if ($this->degrees->contains($degree)) {
            $this->degrees->removeElement($degree);
            // set the owning side to null (unless already changed)
            if ($degree->getIssuance() === $this) {
                $degree->setIssuance(null);
            }
        }
        return $this;
    }
    public function getDegreeField(): ?DegreeField
    {
        return $this->degreeField;
    }
    public function setDegreeField(DegreeField $degreeField): self
    {
        $this->degreeField = $degreeField;
        return $this;
    }
    public function getFaculty(): ?Faculty
    {
        return $this->faculty;
    }
    public function setFaculty(Faculty $faculty): self
    {
        $this->faculty = $faculty;
        return $this;
    }

    public function getFile(): ?Upload
    {
        return $this->file;
    }

    public function setFile(Upload $file): self
    {
        $this->file = $file;

        // set the owning side of the relation if necessary
        if ($this !== $file->getIssuance()) {
            $file->setIssuance($this);
        }

        return $this;
    }

    public function getSmartFile(): ?SmartUpload
    {
        return $this->smartFile;
    }

    public function setSmartFile(SmartUpload $smartFile): self
    {
        $this->smartFile = $smartFile;

        // set the owning side of the relation if necessary
        if ($this !== $smartFile->getIssuance()) {
            $smartFile->setIssuance($this);
        }

        return $this;
    }


    /**
     * @return Collection|GraduateUserInvite[]
     */
    public function getGraduateUserInvites(): Collection
    {
        return $this->graduateUserInvites;
    }

    public function addGraduateUserInvite(GraduateUserInvite $graduateUserInvite): self
    {
        if (!$this->graduateUserInvites->contains($graduateUserInvite)) {
            $this->graduateUserInvites[] = $graduateUserInvite;
            $graduateUserInvite->addIssuance($this);
        }

        return $this;
    }

    public function removeGraduateUserInvite(GraduateUserInvite $graduateUserInvite): self
    {
        if ($this->graduateUserInvites->contains($graduateUserInvite)) {
            $this->graduateUserInvites->removeElement($graduateUserInvite);
            $graduateUserInvite->removeIssuance($this);
        }

        return $this;
    }

    public function getDegreeTitle(): ?DegreeTitle
    {
        return $this->degreeTitle;
    }

    public function setDegreeTitle(?DegreeTitle $degreeTitle): self
    {
        $this->degreeTitle = $degreeTitle;

        return $this;
    }

    public function getProgress(): ?int
    {
        return $this->progress;
    }

    public function setProgress(?int $progress): self
    {
        $this->progress = $progress;

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

}