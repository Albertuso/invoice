<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ContractRepository")
 */
class Contract
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
    private $safe_code;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ProductLine", mappedBy="contract")
     */
    private $line;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Enterprise", inversedBy="contracts")
     * @ORM\JoinColumn(nullable=false)
     */
    private $enterprise;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Client", inversedBy="contracts")
     * @ORM\JoinColumn(nullable=false)
     */
    private $client;

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

    public function getSafeCode(): ?string
    {
        return $this->safe_code;
    }

    public function setSafeCode(string $safe_code): self
    {
        $this->safe_code = $safe_code;

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
            $line->setContract($this);
        }

        return $this;
    }

    public function removeLine(ProductLine $line): self
    {
        if ($this->line->contains($line)) {
            $this->line->removeElement($line);
            // set the owning side to null (unless already changed)
            if ($line->getContract() === $this) {
                $line->setContract(null);
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
}
