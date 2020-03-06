<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\BudgetRepository")
 */
class Budget
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $status;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $psswrd;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ProductLine", mappedBy="budget")
     */
    private $line;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Enterprise", inversedBy="budgets")
     * @ORM\JoinColumn(nullable=false)
     */
    private $enterprise;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Client", inversedBy="budgets")
     * @ORM\JoinColumn(nullable=false)
     */
    private $client;

    /**
     * @ORM\Column(type="text")
     */
    private $contract;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $footer;

    /**
     * @ORM\Column(type="float")
     */
    private $subtotal;

    /**
     * @ORM\Column(type="float")
     */
    private $total;

    /**
     * @ORM\Column(type="date")
     */
    private $date;

    public function __construct()
    {
        $this->line = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(int $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getPsswrd(): ?string
    {
        return $this->psswrd;
    }

    public function setPsswrd(string $psswrd): self
    {
        $this->psswrd = $psswrd;

        return $this;
    }

    /**
     * @return Collection|ProductLine[]
     */
    public function getLine(): Collection
    {
        return $this->line;
    }

    public function addLine(ProductLine $line): self
    {
        if (!$this->line->contains($line)) {
            $this->line[] = $line;
            $line->setBudget($this);
        }

        return $this;
    }

    public function removeLine(ProductLine $line): self
    {
        if ($this->line->contains($line)) {
            $this->line->removeElement($line);
            // set the owning side to null (unless already changed)
            if ($line->getBudget() === $this) {
                $line->setBudget(null);
            }
        }

        return $this;
    }

    public function getEnterprise(): ?Enterprise
    {
        return $this->enterprise;
    }

    public function setEnterprise(?Enterprise $enterprise): self
    {
        $this->enterprise = $enterprise;

        return $this;
    }

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(?Client $client): self
    {
        $this->client = $client;

        return $this;
    }

    public function getContract(): ?string
    {
        return $this->contract;
    }

    public function setContract(string $contract): self
    {
        $this->contract = $contract;

        return $this;
    }
    public function __toString()
    {
        return $this->id;
    }

    public function getFooter(): ?string
    {
        return $this->footer;
    }

    public function setFooter(string $footer): self
    {
        $this->footer = $footer;

        return $this;
    }

    public function getSubtotal(): ?float
    {
        return $this->subtotal;
    }

    public function setSubtotal(float $subtotal): self
    {
        $this->subtotal = $subtotal;

        return $this;
    }

    public function getTotal(): ?float
    {
        return $this->total;
    }

    public function setTotal(float $total): self
    {
        $this->total = $total;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }
}
