<?php

namespace App\Entity;

use App\Entity\Corporate;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Table(name="corporate_user")
 * @ORM\Entity(repositoryClass="App\Repository\CorporateUserRepository")
 */
class CorporateUser
{   
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="UUID")
     * @ORM\Column(type="string", length=36)
     * @Groups({"public"})
     */
    public $id;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Corporate", inversedBy="corporateUsers")
     */
    private $corporate;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\User", mappedBy="corporateUser", cascade={"persist", "remove"})
     */
    private $user;

    public function __construct()
    {
        parent::__construct();
        $this->corporate = new ArrayCollection();
        $this->roles = new ArrayCollection();
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * @return Collection|Corporate[]
     */
    public function getCorporate(): Collection
    {
        return $this->corporate;
    }

    public function addCorporate(Corporate $corporate): self
    {
        if (!$this->corporate->contains($corporate)) {
            $this->corporate[] = $corporate;
        }

        return $this;
    }

    public function removeCorporate(Corporate $corporate): self
    {
        if ($this->corporate->contains($corporate)) {
            $this->corporate->removeElement($corporate);
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
        $newCorporateUser = $user === null ? null : $this;
        if ($newCorporateUser !== $user->getCorporateUser()) {
            $user->setCorporateUser($newCorporateUser);
        }

        return $this;
    }

}