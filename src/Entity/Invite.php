<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\DateTime;

/**
 * @ORM\Entity(repositoryClass="App\Repository\InviteRepository")
 */
class Invite
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
     * @ORM\ManyToOne(targetEntity="App\Entity\Issuance", inversedBy="invitation")
     */
    private $issuance;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\School", inversedBy="invites")
     */
    private $school;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\SchoolUser", inversedBy="invite", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $schoolUser;

    public function __construct()
    {
        $this->creation_date = new \DateTime();
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

    public function getIssuance(): ?Issuance
    {
        return $this->issuance;
    }

    public function setIssuance(?Issuance $issuance): self
    {
        $this->issuance = $issuance;

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

    public function getSchoolUser(): ?SchoolUser
    {
        return $this->schoolUser;
    }

    public function setSchoolUser(SchoolUser $schoolUser): self
    {
        $this->schoolUser = $schoolUser;

        return $this;
    }
}
