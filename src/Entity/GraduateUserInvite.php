<?php

namespace App\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\GraduateUserInviteRepository")
 */
class GraduateUserInvite
{
     /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="UUID")
     * @ORM\Column(type="string", length=36)
     */
    private $id;
    /**
     * @ORM\Column(type="string", length=255)
     */
    private $email;
    /**
     * @ORM\Column(type="string", length=255)
     */
    private $hash;
    /**
     * @ORM\Column(type="datetime")
     */
    private $creation_date;
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\School", inversedBy="graduateUserInvites")
     */
    private $school;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", length=25)
     */
    private $lastname;

    /**
     * @ORM\Column(type="string",length=25, nullable=true)
     */
    private $acceptance;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $acceptanceDate;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Issuance", inversedBy="graduateUserInvites")
     */
    private $issuance;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Degree", mappedBy="GraduateUserInvite")
     */
    private $degrees;
    
    public function __construct()
    {
        $this->creation_date = new \DateTime();
        $this->issuance = new ArrayCollection();
        $this->degrees = new ArrayCollection();
    }
    public function getId(): ?string
    {
        return $this->id;
    }
    public function getEmail(): ?string
    {
        return $this->email;
    }
    public function setEmail(string $email): self
    {
        $this->email = $email;
        return $this;
    }
    public function getHash(): ?string
    {
        return $this->hash;
    }
    public function setHash(string $hash): self
    {
        $this->hash = $hash;
        return $this;
    }
    public function getCreationDate(): ?\DateTimeInterface
    {
        return $this->creation_date;
    }
    public function setCreationDate(\DateTimeInterface $creation_date): self
    {
        $this->creation_date = $creation_date;
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

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getAcceptance(): ?string
    {
        return $this->acceptance;
    }

    public function setAcceptance(?string $acceptance): self
    {
        $this->acceptance = $acceptance;

        return $this;
    }

    public function getAcceptanceDate(): ?\DateTimeInterface
    {
        return $this->acceptanceDate;
    }

    public function setAcceptanceDate(?\DateTimeInterface $acceptanceDate): self
    {
        $this->acceptanceDate = $acceptanceDate;

        return $this;
    }

    /**
     * @return Collection|Issuance[]
     */
    public function getIssuance(): Collection
    {
        return $this->issuance;
    }

    public function addIssuance(Issuance $issuance): self
    {
        if (!$this->issuance->contains($issuance)) {
            $this->issuance[] = $issuance;
        }

        return $this;
    }

    public function removeIssuance(Issuance $issuance): self
    {
        if ($this->issuance->contains($issuance)) {
            $this->issuance->removeElement($issuance);
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
            $degree->setGraduateUserInvite($this);
        }

        return $this;
    }

    public function removeDegree(Degree $degree): self
    {
        if ($this->degrees->contains($degree)) {
            $this->degrees->removeElement($degree);
            // set the owning side to null (unless already changed)
            if ($degree->getGraduateUserInvite() === $this) {
                $degree->setGraduateUserInvite(null);
            }
        }

        return $this;
    }
}
