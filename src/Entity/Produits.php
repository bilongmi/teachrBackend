<?php

namespace App\Entity;

use App\Repository\ProduitsRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ProduitsRepository::class)]
class Produits
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['produits:read', 'produits:write'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['produits:read', 'produits:write'])]
    private ?string $nom = null;

    #[ORM\Column(length: 1000, nullable: true)]
    #[Groups(['produits:read', 'produits:write'])]
    private ?string $description = null;

    #[ORM\Column]
    #[Groups(['produits:read', 'produits:write'])]
    private ?float $prix = null;

    #[ORM\ManyToOne(inversedBy: 'produits')]
    #[Groups(['produits:read'])]
    private ?Categories $categorie = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups(['produits:read', 'produits:write'])]
    private ?\DateTimeInterface $dateCreation = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): static
    {
        $this->prix = $prix;

        return $this;
    }

    public function getCategorie(): ?Categories
    {
        return $this->categorie;
    }

    public function setCategorie(?Categories $categorie): static
    {
        $this->categorie = $categorie;

        return $this;
    }

    public function getDateCreation(): ?\DateTimeInterface
    {
        return $this->dateCreation;
    }

    public function setDateCreation(\DateTimeInterface $dateCreation): static
    {
        $this->dateCreation = $dateCreation;

        return $this;
    }
}
