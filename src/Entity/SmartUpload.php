<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Doctrine\Common\Collections\ArrayCollection;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\Validator\Constraints\Collection;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SmartUploadRepository")
 * @Vich\Uploadable
 */
class SmartUpload
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="UUID")
     * @ORM\Column(type="string", length=36)
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @var string
     */
    private $excel;

    /**
     * @Vich\UploadableField(mapping="issuance_excel", fileNameProperty="excel")
     * @var File
     */
    private $excelFile;

    /**
     * @ORM\Column(type="datetime")
     * @var \DateTime
     */
    private $updatedAt;


    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Issuance", inversedBy="smartFile", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $issuance;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\SchoolUser", inversedBy="smartUploads")
     * @ORM\JoinColumn(nullable=false)
     */
    private $author;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $classYearStart;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $classYearEnd;

    public function __construct()
    {
        $this->author = new ArrayCollection();
    }

    public function getId(): ?string
    {
        return $this->id;
    }
    
    public function setExcelFile(File $excel = null)
    {
        $this->excelFile = $excel;

        // VERY IMPORTANT:
        // It is required that at least one field changes if you are using Doctrine,
        // otherwise the event listeners won't be called and the file is lost
        if ($excel) {
            // if 'updatedAt' is not defined in your entity, use another property
            $this->updatedAt = new \DateTime('now');
        }
    }

    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    public function getExcelFile()
    {
        return $this->excelFile;
    }

    public function setExcel($excel)
    {
        $this->excel = $excel;
    }

    public function getExcel()
    {
        return $this->excel;
    }

    public function getIssuance(): ?Issuance
    {
        return $this->issuance;
    }

    public function setIssuance(Issuance $issuance): self
    {
        $this->issuance = $issuance;

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

    public function getClassYearStart(): ?\DateTimeInterface
    {
        return $this->classYearStart;
    }

    public function setClassYearStart(\DateTimeInterface $classYearStart): self
    {
        $this->classYearStart = $classYearStart;

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
      
}
