<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\ORM\Mapping as ORM;

enum productType: string {

}

#[ORM\Entity(repositoryClass: ProductRepository::class)]
class Product
{

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(nullable: true)]
    private ?float $price = null;

    #[ORM\Column(nullable: true)]
    private ?float $priceBulk = null;

    #[ORM\Column(length: 255)]
    private ?string $url = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $priceXpath = null;

    #[ORM\Column(length: 255)]
    private ?string $type = null;

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

    public function setPrice(float $price): static
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

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): static
    {
        $this->url = $url;

        return $this;
    }

    public function getPriceXpath(): ?string
    {
        return $this->priceXpath;
    }

    public function setPriceXpath(?string $priceXpath): static
    {
        $this->priceXpath = $priceXpath;

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

//    public function isCorrectType(): bool
//    {
//        try {
//            return match($this->type) {
//                "Estradiol Pills" => true,
//                "Estradiol Patches" => true,
//                "Estradiol Gel" => true,
//                "Estradiol Injections" => true,
//                "Progesterone Capsules" => true,
//                "Progesterone Gel" => true,
//                "Progesterone Injections" => true,
//                "Cyproterone Acetate" => true,
//                "Bicalutamide" => true,
//                "Spironolactone" => true,
//                "GnRH Agonists" => true,
//                "Finasteride" => true,
//                "Dutasteride" => true,
//                "Raloxifene" => true,
//                "Tamoxifen" => true,
//                "Clomifene" => true,
//                "Domperidone" => true,
//                "Pioglitazone" => true,
//                "HydroxyProg Injections" => true,
//            };
//        } catch (\UnhandledMatchError) {
//            return false;
//        }
//
//    }
}
