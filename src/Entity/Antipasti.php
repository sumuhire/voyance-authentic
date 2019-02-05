<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AntipastiRepository")
 */
class Antipasti
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Piatto", cascade={"persist", "remove"})
     */
    private $plat1;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Piatto", cascade={"persist", "remove"})
     */
    private $plat2;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Piatto", cascade={"persist", "remove"})
     */
    private $plat3;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Piatto", cascade={"persist", "remove"})
     */
    private $plat4;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\PiattoType", cascade={"persist", "remove"})
     */
    private $piattoType;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPlat1(): ?Piatto
    {
        return $this->plat1;
    }

    public function setPlat1(?Piatto $plat1): self
    {
        $this->plat1 = $plat1;

        return $this;
    }

    public function getPlat2(): ?Piatto
    {
        return $this->plat2;
    }

    public function setPlat2(?Piatto $plat2): self
    {
        $this->plat2 = $plat2;

        return $this;
    }

    public function getPlat3(): ?Piatto
    {
        return $this->plat3;
    }

    public function setPlat3(?Piatto $plat3): self
    {
        $this->plat3 = $plat3;

        return $this;
    }

    public function getPlat4(): ?Piatto
    {
        return $this->plat4;
    }

    public function setPlat4(?Piatto $plat4): self
    {
        $this->plat4 = $plat4;

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
