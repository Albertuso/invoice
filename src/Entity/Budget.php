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
     * @ORM\Column(type="date")
     */
    private $date;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $footer;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\BudgetLine", mappedBy="budget", cascade={"persist"})
     */
    private $line;

    /**
     * @ORM\Column(type="float")
     */
    private $subtotal;

    /**
     * @ORM\Column(type="float")
     */
    private $total;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Client", inversedBy="budgets")
     * @ORM\JoinColumn(nullable=false)
     */
    private $client;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Enterprise", inversedBy="budgets")
     * @ORM\JoinColumn(nullable=false)
     */
    private $enterprise;

    /**
     * @ORM\Column(type="boolean")
     */
    private $visible;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $DescriptionBudget;

    /**
     * @ORM\Column(type="string", length=1)
     */
    private $sold;

    /**
     * @ORM\Column(type="string", length=15)
     */
    private $passwd;

    public function __construct()
    {
        $this->line = new ArrayCollection();
        $this->passwd = $this->randompasswd();
    }
    public function randompasswd(): string
    {
        return substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 15);
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
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

    /**
     * @return Collection|BudgetLine[]
     */
    public function getLine(): Collection
    {
        return $this->line;
    }

    public function addLine(BudgetLine $line): self
    {
        if (!$this->line->contains($line)) {
            $this->line[] = $line;
            $line->setBudget($this);
        }

        return $this;
    }

    public function removeLine(BudgetLine $line): self
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

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(?Client $client): self
    {
        $this->client = $client;

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

    public function getVisible(): ?bool
    {
        return $this->visible;
    }

    public function setVisible(bool $visible): self
    {
        $this->visible = $visible;

        return $this;
    }

    public function getDescriptionBudget(): ?string
    {
        return $this->DescriptionBudget;
    }

    public function setDescriptionBudget(?string $DescriptionBudget): self
    {
        $this->DescriptionBudget = $DescriptionBudget;

        return $this;
    }

    public function getSold(): ?string
    {
        return $this->sold;
    }

    public function setSold(string $sold): self
    {
        $this->sold = $sold;

        return $this;
    }

    public function getPasswd(): ?string
    {
        return $this->passwd;
    }

    public function setPasswd(string $passwd): self
    {
        $this->passwd = $passwd;

        return $this;
    }
}
