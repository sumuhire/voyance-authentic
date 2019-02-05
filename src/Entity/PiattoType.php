<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PiattoTypeRepository")
 */
class PiattoType
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $label;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Piatto", mappedBy="piattoType")
     */
    private $piatto;

    public function __construct()
    {
        $this->piatto = new ArrayCollection();
    }

    public function getId(): ?int
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
     * @return Collection|Piatto[]
     */
    public function getPiatto(): Collection
    {
        return $this->piatto;
    }

    public function addPiatto(Piatto $piatto): self
    {
        if (!$this->piatto->contains($piatto)) {
            $this->piatto[] = $piatto;
            $piatto->setPiattoType($this);
        }

        return $this;
    }

    public function removePiatto(Piatto $piatto): self
    {
        if ($this->piatto->contains($piatto)) {
            $this->piatto->removeElement($piatto);
            // set the owning side to null (unless already changed)
            if ($piatto->getPiattoType() === $this) {
                $piatto->setPiattoType(null);
            }
        }

        return $this;
    }

}
