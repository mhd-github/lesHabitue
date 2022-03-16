<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\CommerceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=CommerceRepository::class)
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

    public function __construct()
    {
        $this->portefeuilles = new ArrayCollection();
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
}
