<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\CommerceRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=CommerceRepository::class)
 * @UniqueEntity(
 *      fields={"nom"},
 *      errorPath="nom",
 *      message="Ce client existe déjà."
 * )
 */
class Commerce
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $adresse;

    /**
     * @ORM\OneToMany(targetEntity=Portefeuille::class, mappedBy="commerce", orphanRemoval=true)
     */
    private $portefeuilles;

    /**
     * @ORM\OneToMany(targetEntity=Transaction::class, mappedBy="commerce", orphanRemoval=true)
     */
    private $transactions;

    public function __construct()
    {
        $this->portefeuilles = new ArrayCollection();
        $this->transactions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    /**
     * @return Collection<int, Portefeuille>
     */
    public function getPortefeuilles(): Collection
    {
        return $this->portefeuilles;
    }

    public function addPortefeuille(Portefeuille $portefeuille): self
    {
        if (!$this->portefeuilles->contains($portefeuille)) {
            $this->portefeuilles[] = $portefeuille;
            $portefeuille->setCommerce($this);
        }

        return $this;
    }

    public function removePortefeuille(Portefeuille $portefeuille): self
    {
        if ($this->portefeuilles->removeElement($portefeuille)) {
            // set the owning side to null (unless already changed)
            if ($portefeuille->getCommerce() === $this) {
                $portefeuille->setCommerce(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Transaction>
     */
    public function getTransactions(): Collection
    {
        return $this->transactions;
    }

    public function addTransaction(Transaction $transaction): self
    {
        if (!$this->transactions->contains($transaction)) {
            $this->transactions[] = $transaction;
            $transaction->setCommerce($this);
        }

        return $this;
    }

    public function removeTransaction(Transaction $transaction): self
    {
        if ($this->transactions->removeElement($transaction)) {
            // set the owning side to null (unless already changed)
            if ($transaction->getCommerce() === $this) {
                $transaction->setCommerce(null);
            }
        }

        return $this;
    }
}
