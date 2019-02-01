<?php
namespace App\Entity;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;
/**
 * @ORM\Entity(repositoryClass="App\Repository\SchoolRepository")
 */
class School
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="UUID")
     * @ORM\Column(type="string", length=36)
     * @Groups({"public"})
     */
    public $id;
    /**
     * @ORM\Column(type="string", length=60)
     */
    public $name;
    /**
     * @ORM\Column(type="string", length=60)
     */
    public $type;
    /**
     * @ORM\Column(type="date")
     */
    public $establishmentDate;
    /**
     * @ORM\Column(type="datetime")
     */
    public $creationDate;
    /**
     * @ORM\Column(type="string", length=40)
     */
    public $location;
    /**
     * @ORM\Column(type="string", length=40)
     */
    public $website;
    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    public $motto;
    /**
     * @ORM\Column(type="text")
     */
    public $aboutUs;
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Image()
     * @Groups({"public"})
     */
    public $logo;
    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\GraduateUser", mappedBy="school")
     */
    public $graduateUsers;
    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Issuance", mappedBy="school", orphanRemoval=true)
     */
    public $issuances;
    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Faculty", mappedBy="school", orphanRemoval=true)
     */
    public $faculties;
    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Invite", mappedBy="school")
     */
    private $invites;
    /**
     * @ORM\OneToMany(targetEntity="App\Entity\GraduateUserInvite", mappedBy="school")
     */
    private $graduateUserInvites;
    /**
     * @ORM\OneToMany(targetEntity="App\Entity\SchoolUser", mappedBy="school")
     */
    private $schoolUsers;
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $picturePresentation;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\DegreeTitle", mappedBy="school", orphanRemoval=true, cascade={"persist"})
     */
    private $degreeTitles;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Degree", mappedBy="school")
     */
    private $degrees;
    
    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->graduateUsers = new ArrayCollection();
        $this->issuances = new ArrayCollection();
        $this->faculties = new ArrayCollection();
        $this->creationDate = new \DateTime();
        $this->schoolUsers = new ArrayCollection();
        $this->invites = new ArrayCollection();
        $this->degreeTitles = new ArrayCollection();
        $this->degrees = new ArrayCollection();
    }
    public function getId(): ?string
    {
        return $this->id;
    }
    public function getName(): ?string
    {
        return $this->name;
    }
    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }
    public function getType(): ?string
    {
        return $this->type;
    }
    public function setType(string $type): self
    {
        $this->type = $type;
        return $this;
    }
    public function getEstablishmentDate(): ?\DateTimeInterface
    {
        return $this->establishmentDate;
    }
    public function setEstablishmentDate(\DateTimeInterface $establishmentDate): self
    {
        $this->establishmentDate = $establishmentDate;
        return $this;
    }
    public function getCreationDate(): ?\DateTimeInterface
    {
        return $this->creationDate;
    }
    public function getLocation(): ?string
    {
        return $this->location;
    }
    public function setLocation(string $location): self
    {
        $this->location = $location;
        return $this;
    }
    public function getWebsite(): ?string
    {
        return $this->website;
    }
    public function setWebsite(string $website): self
    {
        $this->website = $website;
        return $this;
    }
    public function getMotto(): ?string
    {
        return $this->motto;
    }
    public function setMotto(?string $motto): self
    {
        $this->motto = $motto;
        return $this;
    }
    public function getAboutUs(): ?string
    {
        return $this->aboutUs;
    }
    public function setAboutUs(string $aboutUs): self
    {
        $this->aboutUs = $aboutUs;
        return $this;
    }
    public function getLogo(): ?string
    {
        return $this->logo;
    }
    public function setLogo(string $logo): self
    {
        $this->logo = $logo;
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
            $graduateUser->addSchool($this);
        }
        return $this;
    }
    public function removeGraduateUser(GraduateUser $graduateUser): self
    {
        if ($this->graduateUsers->contains($graduateUser)) {
            $this->graduateUsers->removeElement($graduateUser);
            $graduateUser->removeSchool($this);
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
            $issuance->setSchool($this);
        }
        return $this;
    }
    public function removeIssuance(Issuance $issuance): self
    {
        if ($this->issuances->contains($issuance)) {
            $this->issuances->removeElement($issuance);
            // set the owning side to null (unless already changed)
            if ($issuance->getSchool() === $this) {
                $issuance->setSchool(null);
            }
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
            $faculty->setSchool($this);
        }
        return $this;
    }
    public function removeFaculty(Faculty $faculty): self
    {
        if ($this->faculties->contains($faculty)) {
            $this->faculties->removeElement($faculty);
            // set the owning side to null (unless already changed)
            if ($faculty->getSchool() === $this) {
                $faculty->setSchool(null);
            }
        }
        return $this;
    }
    /**
     * @return Collection|Invite[]
     */
    public function getInvites(): Collection
    {
        return $this->invites;
    }
    public function addInvite(Invite $invite): self
    {
        if (!$this->invites->contains($invite)) {
            $this->invites[] = $invite;
            $invite->setSchool($this);
        }
        return $this;
    }
    public function removeInvite(Invite $invite): self
    {
        if ($this->invites->contains($invite)) {
            $this->invites->removeElement($invite);
            // set the owning side to null (unless already changed)
            if ($invite->getSchool() === $this) {
                $invite->setSchool(null);
            }
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
        if (!$this->invites->contains($graduateUserInvite)) {
            $this->graduateUserInvites[] = $graduateUserInvite;
            $graduateUserInvite->setSchool($this);
        }
        return $this;
    }
    public function removeGraduateUserInvite(GraduateUserInvite $graduateUserInvite): self
    {
        if ($this->graduateUserInvites->contains($graduateUserInvite)) {
            $this->graduateUserInvites->removeElement($graduateUserInvite);
            // set the owning side to null (unless already changed)
            if ($graduateUserInvite->getSchool() === $this) {
                $graduateUserInvite->setSchool(null);
            }
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
            $schoolUser->setSchool($this);
        }
        return $this;
    }
    public function removeSchoolUser(SchoolUser $schoolUser): self
    {
        if ($this->schoolUsers->contains($schoolUser)) {
            $this->schoolUsers->removeElement($schoolUser);
            // set the owning side to null (unless already changed)
            if ($schoolUser->getSchool() === $this) {
                $schoolUser->setSchool(null);
            }
        }
        return $this;
    }
    public function getPicturePresentation(): ?string
    {
        return $this->picturePresentation;
    }
    public function setPicturePresentation(?string $picturePresentation): self
    {
        $this->picturePresentation = $picturePresentation;
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
            $degreeTitle->setSchool($this);
        }

        return $this;
    }

    public function removeDegreeTitle(DegreeTitle $degreeTitle): self
    {
        if ($this->degreeTitles->contains($degreeTitle)) {
            $this->degreeTitles->removeElement($degreeTitle);
            // set the owning side to null (unless already changed)
            if ($degreeTitle->getSchool() === $this) {
                $degreeTitle->setSchool(null);
            }
        }

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
            $degree->setSchool($this);
        }

        return $this;
    }

    public function removeDegree(Degree $degree): self
    {
        if ($this->degrees->contains($degree)) {
            $this->degrees->removeElement($degree);
            // set the owning side to null (unless already changed)
            if ($degree->getSchool() === $this) {
                $degree->setSchool(null);
            }
        }

        return $this;
    }

}