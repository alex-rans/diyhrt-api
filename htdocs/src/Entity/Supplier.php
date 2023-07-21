<?php

namespace App\Entity;

use App\Repository\SupplierRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SupplierRepository::class)]
class Supplier
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $shipping = null;

    #[ORM\Column(type: Types::ARRAY)]
    private array $paymentMethods = [];

    #[ORM\Column(length: 255)]
    private ?string $url = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $notes = null;

//    #[ORM\OneToMany(mappedBy: 'supplier', targetEntity: Product::class)]
//    private Collection $products;

    public function __construct()
    {
        $this->products = new ArrayCollection();
    }

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

    public function getShipping(): ?string
    {
        return $this->shipping;
    }

    public function setShipping(string $shipping): static
    {
        $this->shipping = $shipping;

        return $this;
    }

    public function getPaymentMethods(): array
    {
        return $this->paymentMethods;
    }

    public function setPaymentMethods(array $paymentMethods): static
    {
        $this->paymentMethods = $paymentMethods;

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

    public function getNotes(): ?string
    {
        return $this->notes;
    }

    public function setNotes(?string $notes): static
    {
        $this->notes = $notes;

        return $this;
    }

//    /**
//     * @return Collection<int, Product>
//     */
//    public function getProducts(): Collection
//    {
//        return $this->products;
//    }

//    public function addProduct(Product $product): static
//    {
//        if (!$this->products->contains($product)) {
//            $this->products->add($product);
//            $product->setSupplier($this);
//        }
//
//        return $this;
//    }
//
//    public function removeProduct(Product $product): static
//    {
//        if ($this->products->removeElement($product)) {
//            // set the owning side to null (unless already changed)
//            if ($product->getSupplier() === $this) {
//                $product->setSupplier(null);
//            }
//        }
//
//        return $this;
//    }
}
