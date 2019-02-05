<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PiattoRepository")
 */
class Piatto
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
    private $italien;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $francais;

    /**
     * @ORM\Column(type="string", length=30)
     */
    private $prix;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\PiattoType", inversedBy="piatto")
     * @ORM\JoinColumn(nullable=false)
     */
    private $piattoType;

    public function __construct()
    {
        $this->piattoTypes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getItalien(): ?string
    {
        return $this->italien;
    }

    public function setItalien(string $italien): self
    {
        $this->italien = $italien;

        return $this;
    }

    public function getfrancais(): ?string
    {
        return $this->francais;
    }

    public function setfrancais(string $francais): self
    {
        $this->francais = $francais;

        return $this;
    }

    public function getPrix(): ?string
    {
        return $this->prix;
    }

    public function setPrix(string $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    public function getPiattoType(): ?PiattoType
    {
        return $this->piattoType;
    }

    public function setPiattoType(?PiattoType $piattoType): self
    {
        $this->piattoType = $piattoType;

        return $this;
    }

}
