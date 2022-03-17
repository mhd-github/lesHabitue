<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\PortefeuilleRepository;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=PortefeuilleRepository::class)
 * @UniqueEntity(
 *     fields={"user", "commerce"},
 *     errorPath="commerce",
 *     message="Ce client a déjà un portefeuille chez ce commerçant."
 * )
 */
class Portefeuille
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * 
     * @Assert\PositiveOrZero(
     *      message="Cette valeur doit être positive ou nulle"
     * )
     *
     * @ORM\Column(type="float")
     */
    private $solde;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="portefeuilles")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=Commerce::class, inversedBy="portefeuilles")
     * @ORM\JoinColumn(nullable=false)
     */
    private $commerce;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSolde(): ?float
    {
        return $this->solde;
    }

    public function setSolde(float $solde): self
    {
        $this->solde = $solde;

        return $this;
    }

    public function getUser(): ?user
    {
        return $this->user;
    }

    public function setUser(?user $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getCommerce(): ?commerce
    {
        return $this->commerce;
    }

    public function setCommerce(?commerce $commerce): self
    {
        $this->commerce = $commerce;

        return $this;
    }
}
