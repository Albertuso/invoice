<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\EnterpriseRepository")
 */
class Enterprise
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
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $address;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $telephone;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $web;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nif;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $logo;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $footer;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Product", mappedBy="enterprise")
     */
    private $products;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="enterprises")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Client", mappedBy="enterprise")
     */
    private $clients;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Invoice", mappedBy="enterprise")
     */
    private $invoices;

    /**
     * @ORM\Column(type="integer")
     */
    private $nextinvoicenumber;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\SocialNetwork", mappedBy="enterprise")
     */
    private $socialnetwork;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Supervisor", mappedBy="enterprise")
     */
    private $supervisors;

    /**
     * @ORM\Column(type="boolean")
     */
    private $visible;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Contract", mappedBy="enterprise")
     */
    private $contracts;

    public function __construct()
    {
        $this->products = new ArrayCollection();
        $this->clients = new ArrayCollection();
        $this->invoices = new ArrayCollection();
        $this->socialnetwork = new ArrayCollection();
        $this->supervisors = new ArrayCollection();
        $this->contracts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(string $telephone): self
    {
        $this->telephone = $telephone;

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

    public function getWeb(): ?string
    {
        return $this->web;
    }

    public function setWeb(string $web): self
    {
        $this->web = $web;

        return $this;
    }

    public function getNif(): ?string
    {
        return $this->nif;
    }

    public function setNif(string $nif): self
    {
        $this->nif = $nif;

        return $this;
    }

    public function getLogo(): ?string
    {
        return $this->logo;
    }

    public function setLogo(string $logo): self
    {
        $this->logo = $logo;

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
     * @return Collection|Product[]
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function addProduct(Product $product): self
    {
        if (!$this->products->contains($product)) {
            $this->products[] = $product;
            $product->setEnterprise($this);
        }

        return $this;
    }

    public function removeProduct(Product $product): self
    {
        if ($this->products->contains($product)) {
            $this->products->removeElement($product);
            // set the owning side to null (unless already changed)
            if ($product->getEnterprise() === $this) {
                $product->setEnterprise(null);
            }
        }

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
    public function __toString()
    {
        return $this->name;
    }

    /**
     * @return Collection|Client[]
     */
    public function getClients(): Collection
    {
        return $this->clients;
    }

    public function addClient(Client $client): self
    {
        if (!$this->clients->contains($client)) {
            $this->clients[] = $client;
            $client->setEnterprise($this);
        }

        return $this;
    }

    public function removeClient(Client $client): self
    {
        if ($this->clients->contains($client)) {
            $this->clients->removeElement($client);
            // set the owning side to null (unless already changed)
            if ($client->getEnterprise() === $this) {
                $client->setEnterprise(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Invoice[]
     */
    public function getInvoices(): Collection
    {
        return $this->invoices;
    }

    public function addInvoice(Invoice $invoice): self
    {
        if (!$this->invoices->contains($invoice)) {
            $this->invoices[] = $invoice;
            $invoice->setEnterprise($this);
        }

        return $this;
    }

    public function removeInvoice(Invoice $invoice): self
    {
        if ($this->invoices->contains($invoice)) {
            $this->invoices->removeElement($invoice);
            // set the owning side to null (unless already changed)
            if ($invoice->getEnterprise() === $this) {
                $invoice->setEnterprise(null);
            }
        }

        return $this;
    }

    public function getNextinvoicenumber(): ?int
    {
        return $this->nextinvoicenumber;
    }

    public function setNextinvoicenumber(int $nextinvoicenumber): self
    {
        $this->nextinvoicenumber = $nextinvoicenumber;

        return $this;
    }

    /**
     * @return Collection|SocialNetwork[]
     */
    public function getSocialnetwork(): Collection
    {
        return $this->socialnetwork;
    }

    public function addSocialnetwork(SocialNetwork $socialnetwork): self
    {
        if (!$this->socialnetwork->contains($socialnetwork)) {
            $this->socialnetwork[] = $socialnetwork;
            $socialnetwork->setEnterprise($this);
        }

        return $this;
    }

    public function removeSocialnetwork(SocialNetwork $socialnetwork): self
    {
        if ($this->socialnetwork->contains($socialnetwork)) {
            $this->socialnetwork->removeElement($socialnetwork);
            // set the owning side to null (unless already changed)
            if ($socialnetwork->getEnterprise() === $this) {
                $socialnetwork->setEnterprise(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Supervisor[]
     */
    public function getSupervisors(): Collection
    {
        return $this->supervisors;
    }

    public function addSupervisor(Supervisor $supervisor): self
    {
        if (!$this->supervisors->contains($supervisor)) {
            $this->supervisors[] = $supervisor;
            $supervisor->setEnterprise($this);
        }

        return $this;
    }

    public function removeSupervisor(Supervisor $supervisor): self
    {
        if ($this->supervisors->contains($supervisor)) {
            $this->supervisors->removeElement($supervisor);
            // set the owning side to null (unless already changed)
            if ($supervisor->getEnterprise() === $this) {
                $supervisor->setEnterprise(null);
            }
        }

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

    /**
     * @return Collection|Contract[]
     */
    public function getContracts(): Collection
    {
        return $this->contracts;
    }

    public function addContract(Contract $contract): self
    {
        if (!$this->contracts->contains($contract)) {
            $this->contracts[] = $contract;
            $contract->setEnterprise($this);
        }

        return $this;
    }

    public function removeContract(Contract $contract): self
    {
        if ($this->contracts->contains($contract)) {
            $this->contracts->removeElement($contract);
            // set the owning side to null (unless already changed)
            if ($contract->getEnterprise() === $this) {
                $contract->setEnterprise(null);
            }
        }

        return $this;
    }
}
