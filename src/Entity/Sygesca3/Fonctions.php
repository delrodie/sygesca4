<?php

namespace App\Entity\Sygesca3;

use App\Repository\FonctionsRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * Fonctions
 *
 * @ORM\Table(name="fonctions")
 * @ORM\Entity(repositoryClass=FonctionsRepository::class)
 */
class Fonctions
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string|null
     *
     * @ORM\Column(name="libelle", type="string", length=255, nullable=true)
     */
    private $libelle;

    /**
     * @var string|null
     *
     * @ORM\Column(name="montant", type="string", length=255, nullable=true)
     */
    private $montant;

    /**
     * @var string|null
     *
     * @ORM\Column(name="created_at", type="string", length=255, nullable=true)
     */
    private $createdAt;

    /**
     * @var string|null
     *
     * @ORM\Column(name="updated_at", type="string", length=255, nullable=true)
     */
    private $updatedAt;

    /**
     * @var int|null
     *
     * @ORM\Column(name="ristourne", type="integer", nullable=true)
     */
    private $ristourne;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(?string $libelle): self
    {
        $this->libelle = $libelle;

        return $this;
    }

    public function getMontant(): ?string
    {
        return $this->montant;
    }

    public function setMontant(?string $montant): self
    {
        $this->montant = $montant;

        return $this;
    }

    public function getCreatedAt(): ?string
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?string $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?string
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?string $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getRistourne(): ?int
    {
        return $this->ristourne;
    }

    public function setRistourne(?int $ristourne): self
    {
        $this->ristourne = $ristourne;

        return $this;
    }


}
