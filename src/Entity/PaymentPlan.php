<?php

namespace App\Entity;

use App\Entity\SchoolYear;
use App\Repository\PaymentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * PaymentPlan
 *
 * @ORM\Table(name="payment_plan")
 * @ORM\Entity(repositoryClass=PaymentRepository::class)
 */
class PaymentPlan
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
     * @ORM\OneToOne(targetEntity=SchoolYear::class,inversedBy="paymentPlan")
     * @ORM\JoinColumn(name="school_year_id", referencedColumnName="id", nullable=true)
     */
    private $schoolYear;


   

    
     /**
     * @ORM\OneToMany(targetEntity=Installment::class, mappedBy="paymentPlan")
     */
    private $installments;
  
     /**
     * @ORM\Column(type="integer", options={"default" = 0})
     *  
     */
    private $weight;
   

    public function __construct()
    {
        $this->payments = new ArrayCollection();
        $this->installments = new ArrayCollection();
        $this->weight = 1;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSchoolYear(): ?SchoolYear
    {
        return $this->schoolYear;
    }

    public function setSchoolYear(?SchoolYear $schoolYear): static
    {
        $this->schoolYear = $schoolYear;

        return $this;
    }

 
    
    /**
     * @return Collection<int, Installment>
     */
    public function getInstallments(): Collection
    {
        return $this->installments;
    }

    public function addInstallment(Installment $installment): static
    {
        if (!$this->installments->contains($installment)) {
            $this->installments->add($installment);      
        }

        return $this;
    }

    public function removeInstallment(Installment $installment): static
    {
        $this->installments->removeElement($installment);
        return $this;
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
}
