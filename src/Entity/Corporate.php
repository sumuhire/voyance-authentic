<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CorporateRepository")
 */
class Corporate
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="UUID")
     * @ORM\Column(type="string", length=36)
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=40)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=60)
     */
    private $type;

    /**
     * @ORM\Column(type="datetime")
     */
    private $establishmentDate;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $headquarterLocation;

    /**
     * @ORM\Column(type="array", nullable=true)
     */
    private $Locations;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $areaServed;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $website;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $motto;

    /**
     * @ORM\Column(type="text")
     */
    private $aboutUs;

    /**
     * @ORM\Column(type="array")
     */
    private $services;

    /**
     * @ORM\Column(type="array")
     */
    private $industry;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\CorporateUser", mappedBy="corporate")
     */
    private $corporateUsers;

    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->corporateUsers = new ArrayCollection();
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

    public function getHeadquarterLocation(): ?string
    {
        return $this->headquarterLocation;
    }

    public function setHeadquarterLocation(?string $headquarterLocation): self
    {
        $this->headquarterLocation = $headquarterLocation;

        return $this;
    }

    public function getLocations(): ?array
    {
        return $this->Locations;
    }

    public function setLocations(?array $Locations): self
    {
        $this->Locations = $Locations;

        return $this;
    }

    public function getAreaServed(): ?string
    {
        return $this->areaServed;
    }

    public function setAreaServed(?string $areaServed): self
    {
        $this->areaServed = $areaServed;

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

    public function getServices(): ?array
    {
        return $this->services;
    }

    public function setServices(array $services): self
    {
        $this->services = $services;

        return $this;
    }

    public function getIndustry(): ?array
    {
        return $this->industry;
    }

    public function setIndustry(array $industry): self
    {
        $this->industry = $industry;

        return $this;
    }

    /**
     * @return Collection|CorporateUser[]
     */
    public function getCorporateUsers(): Collection
    {
        return $this->corporateUsers;
    }

    public function addCorporateUser(CorporateUser $corporateUser): self
    {
        if (!$this->corporateUsers->contains($corporateUser)) {
            $this->corporateUsers[] = $corporateUser;
            $corporateUser->addCorporate($this);
        }

        return $this;
    }

    public function removeCorporateUser(CorporateUser $corporateUser): self
    {
        if ($this->corporateUsers->contains($corporateUser)) {
            $this->corporateUsers->removeElement($corporateUser);
            $corporateUser->removeCorporate($this);
        }

        return $this;
    }
}
