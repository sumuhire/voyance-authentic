<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DegreeRepository")
 */
class Degree
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="UUID")
     * @ORM\Column(type="string", length=36)
     * @Groups({"public"})
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Issuance", inversedBy="degrees", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $issuance;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\GraduateUser", inversedBy="degrees", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $graduateUser;

    /**
     * @ORM\Column(type="string", length=80, nullable=true)
     */
    private $honor;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\DegreeTitle", inversedBy="degrees", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $degreeTitle;

    /**
     * @ORM\Column(type="date")
     */
    private $classYearEnd;

    /**
     * @ORM\Column(type="date")
     */
    private $classYearStart;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\GraduateUserInvite", inversedBy="degrees", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $GraduateUserInvite;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\SchoolUser", inversedBy="degreesIssued", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $author;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $Firstname;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $lastname;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\School", inversedBy="degrees")
     * @ORM\JoinColumn(nullable=false)
     */
    private $school;

    public function __construct()
    {
    
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getIssuance(): ?Issuance
    {
        return $this->issuance;
    }

    public function setIssuance(?Issuance $issuance): self
    {
        $this->issuance = $issuance;

        return $this;
    }

    public function getGraduateUser(): ?GraduateUser
    {
        return $this->graduateUser;
    }

    public function setGraduateUser(?GraduateUser $graduateUser): self
    {
        $this->graduateUser = $graduateUser;

        return $this;
    }

    public function getHonor(): ?string
    {
        return $this->honor;
    }

    public function setHonor(?string $honor): self
    {
        $this->honor = $honor;

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

    public function getGraduateUserInvite(): ?GraduateUserInvite
    {
        return $this->GraduateUserInvite;
    }

    public function setGraduateUserInvite(?GraduateUserInvite $GraduateUserInvite): self
    {
        $this->GraduateUserInvite = $GraduateUserInvite;

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

    public function getFirstname(): ?string
    {
        return $this->Firstname;
    }

    public function setFirstname(string $Firstname): self
    {
        $this->Firstname = $Firstname;

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

    public function getSchool(): ?School
    {
        return $this->school;
    }

    public function setSchool(?School $school): self
    {
        $this->school = $school;

        return $this;
    }

}
