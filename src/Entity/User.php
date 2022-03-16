<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\Table(name="`user`")
 */
class User
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
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $prenom;

    /**
     * @ORM\Column(type="integer")
     */
    private $numeroTele;

    /**
     * @ORM\OneToMany(targetEntity=Portefeuille::class, mappedBy="user", orphanRemoval=true)
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

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(?string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getNumeroTele(): ?int
    {
        return $this->numeroTele;
    }

    public function setNumeroTele(int $numeroTele): self
    {
        $this->numeroTele = $numeroTele;

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
            $portefeuille->setUser($this);
        }

        return $this;
    }

    public function removePortefeuille(Portefeuille $portefeuille): self
    {
        if ($this->portefeuilles->removeElement($portefeuille)) {
            // set the owning side to null (unless already changed)
            if ($portefeuille->getUser() === $this) {
                $portefeuille->setUser(null);
            }
        }

        return $this;
    }
}
