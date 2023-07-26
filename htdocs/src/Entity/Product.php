<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\ORM\Mapping as ORM;
use OpenApi\Attributes as OA;

#[OA\Schema]
#[ORM\Entity(repositoryClass: ProductRepository::class)]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[OA\Property]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[OA\Property]
    private ?string $name = null;

    #[ORM\Column(nullable: true)]
    #[OA\Property]
    private ?float $price = null;

    #[ORM\Column(nullable: true)]
    #[OA\Property]
    private ?float $priceBulk = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[OA\Property]
    private ?string $prixeXpath = null;

    #[ORM\Column(length: 255)]
    #[OA\Property]
    private ?string $type = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[OA\Property]
    private ?string $notes = null;

    #[ORM\ManyToOne(inversedBy: 'products')]
    #[OA\Property]
    private ?Supplier $supplier = null;

    #[ORM\Column(length: 255)]
    #[OA\Property]
    private ?string $url = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(?float $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getPriceBulk(): ?float
    {
        return $this->priceBulk;
    }

    public function setPriceBulk(?float $priceBulk): static
    {
        $this->priceBulk = $priceBulk;

        return $this;
    }

    public function getPrixeXpath(): ?string
    {
        return $this->prixeXpath;
    }

    public function setPrixeXpath(string $prixeXpath): static
    {
        $this->prixeXpath = $prixeXpath;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getNotes(): ?string
    {
        return $this->notes;
    }

    public function setNotes(?string $notes): static
    {
        $this->notes = $notes;

        return $this;
    }

    public function getSupplier(): ?Supplier
    {
        return $this->supplier;
    }

    public function setSupplier(?Supplier $supplier): static
    {
        $this->supplier = $supplier;

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): static
    {
        $this->url = $url;

        return $this;
    }
}
