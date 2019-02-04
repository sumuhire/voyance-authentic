<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\EquatableInterface;

/**
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="UUID")
     * @ORM\Column(type="string", length=36)
     * @Groups({"public"})
     */
    public $id;

     /**
     * @ORM\Column(type="string", length=10, unique=true, nullable=true)
     * @Assert\Regex("/^\w+/")
     * @Groups({"public"})
     */
    private $username;
    

    /**
     * @ORM\Column(type="string", length=10, nullable=true)
     */
    protected $gender;

    /**
     * @ORM\Column(type="string", length=25)
     * @Assert\NotBlank()
     * @Assert\Regex("/^\w+/")
     * @Groups({"public"})
     */
    protected $firstname;

    /**
     * @ORM\Column(type="string", length=25, nullable=true)
     * @Assert\NotBlank()
     * @Assert\Regex("/^\w+/")
     * @Groups({"public"})
     */
    protected $lastname;

    /**
     * @ORM\Column(type="string", length=100, unique=true)
     * @Assert\NotBlank()
     * @Assert\Regex("/^\w+/")
     * @Groups({"public"})
     */
    protected $email;

    /**
     * @ORM\Column(type="string", length=30, nullable=true)
     */
    protected $phoneFix;

    /**
     * @ORM\Column(type="string", length=25, nullable=true)
     * 
     */
    protected $phoneMobile;

    /**
     * @ORM\Column(type="string", length=40, nullable=true)
     */
    protected $flag;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Regex("/[a-zA-Z0-9]/",
     *  message="Your password must contain a lowercase letter, a uppercaseletter and a number")
     */
    protected $password;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     * @Assert\Image()
     * @Groups({"public"})
     */
    protected $picture;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $creationDate;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    protected $birthDate;

     /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Role")
     * @ORM\JoinColumn(nullable=false)
     * @ORM\JoinTable(
     *      name="user_role",
     *      joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="role_id", referencedColumnName="id")}
     * )
     */
    protected $roles;

    public function __construct()
    {
        $this->creationDate = new \DateTime();
        $this->flag = "activate";
        $this->reports = new ArrayCollection();
    }

    public function getGender(): ?string
    {
        return $this->gender;
    }

    public function setGender(string $gender): self
    {
        $this->gender = $gender;

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

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPhoneFix(): ?string
    {
        return $this->phoneFix;
    }

    public function setPhoneFix(string $phoneFix): self
    {
        $this->phoneFix = $phoneFix;

        return $this;
    }

    public function getPhoneMobile(): ?string
    {
        return $this->phoneMobile;
    }

    public function setPhoneMobile(string $phoneMobile): self
    {
        $this->phoneMobile = $phoneMobile;

        return $this;
    }

    public function getFlag(): ?string
    {
        return $this->flag;
    }

    public function setFlag(string $flag): self
    {
        $this->flag = $flag;

        return $this;
    }

    public function getPicture(): ?string
    {
        return $this->picture;
    }

    public function setPicture(string $picture): self
    {
        $this->picture = $picture;

        return $this;
    }

    public function getCreationDate()
    {
        return $this->creationDate;
    }

    public function getBirthDate(): ?\DateTimeInterface
    {
        return $this->birthDate;
    }

    public function setBirthDate(\DateTimeInterface $birthDate): self
    {
        $this->birthDate = $birthDate;

        return $this;
    }

    public function eraseCredentials()
    {
        return null;
    }
    public function getSalt()
    {
        return null;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @return Role[]
     */
    public function getRoles() : array
    {
        return array_map('strval', $this->roles->toArray());      
    }
    public function setRoles(Role $role)
    {
        $this->roles = new ArrayCollection();
        $this->addRole($role);
        return $this;
    }
    /**
     * @return Role[]
     */
    public function getRole() : array
    {
        return array_map('strval', $this->roles->toArray());
    }
    public function addRole(Role $role) : self
    {
        if (!$this->roles->contains($role)) {
            $this->roles[] = $role;
        }
        return $this;
    }
    public function removeRole(Role $role) : self
    {
        if ($this->roles->contains($role)) {
            $this->roles->removeElement($role);
        }
        return $this;
    }

}
