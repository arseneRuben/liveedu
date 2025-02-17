<?php

namespace App\Entity;

use App\Repository\AbscenceRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AbscenceRepository::class)
 */
class Abscence
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    /**
     * @ORM\Column(type="integer")
     */
    private $weight;


    /**
     * @ORM\Column(type="string", length=255)
     */
    private $reason;
    /**
     * Cette abscence est t-elle justifiee ?
     * @ORM\Column(type="boolean", options={"default" = false})
     */
    private $justified = false;

    /**
     * @ORM\ManyToOne(targetEntity=AbscenceSheet::class, inversedBy="abscences")
     * @ORM\JoinColumn(nullable=false)
     */
    private $abscenceSheet;
    /**
     * @ORM\ManyToOne(targetEntity=Student::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $student;



    public function getId(): ?int
    {
        return $this->id;
    }
    public function getWeight(): ?int
    {
        return $this->weight;
    }

    public function setWeight(int $weight): self
    {
        $this->weight = $weight;

        return $this;
    }

    public function getStudent(): ?Student
    {
        return $this->student;
    }

    public function setStudent(?Student $student): self
    {
        $this->student = $student;

        return $this;
    }

    public function getAbscenceSheet(): ?AbscenceSheet
    {
        return $this->abscenceSheet;
    }

    public function setAbscenceSheet(?AbscenceSheet $abscenceSheet): self
    {
        $this->abscenceSheet = $abscenceSheet;

        return $this;
    }




    public function getReason(): ?string
    {
        return $this->reason;
    }

    public function setReason(string $reason): static
    {
        $this->reason = $reason;

        return $this;
    }

    public function isJustified(): ?bool
    {
        return $this->justified;
    }

    public function setJustified(bool $justified): static
    {
        $this->justified = $justified;

        return $this;
    }
}
