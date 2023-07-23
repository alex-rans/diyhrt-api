<?php

namespace App\Service;

use App\Entity\Product;
use App\Entity\Supplier;
use Doctrine\ORM\EntityManagerInterface;
use Spekulatius\PHPScraper\PHPScraper;

class ProductScraper
{
    private $choiceArray = [
        "Estradiol Pills",
        "Estradiol Patches",
        "Estradiol Gel",
        "Estradiol Injections",
        "Progesterone Capsules",
        "Progesterone Gel",
        "Progesterone Injections",
        "Cyproterone Acetate",
        "Bicalutamide",
        "Spironolactone",
        "Gonadotropin-Releasing Hormone Agonists",
        "Finasteride",
        "Dutasteride",
        "Raloxifene",
        "Tamoxifen",
        "Clomifene",
        "Domperidone",
        "Pioglitazone",
        "Hydroxyprogesterone Caproate Injections",
    ];
    private EntityManagerInterface $entityManager;


    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function getChoices(): array
    {
        return $this->choiceArray;
    }

    private function init(string $url): PHPScraper
    {
        $web = new \Spekulatius\PHPScraper\PHPScraper;
        $web->go($url);
        return $web;
    }
    public function getPriceData(array $productTypes){
        foreach ($productTypes as $productType){
            $products = $this->entityManager->getRepository(Product::class)->findBy(["type" => $productType]);
            foreach ($products as $product){
                $web = $this->init($product->getUrl());
                $price = $web->filter($product->getPrixeXpath())->text();
                $price = preg_replace('/[^0-9.]+/', '', $price);
                $price = (float) $price;
                if ($price != $product->getPrice()) {
                    $product->setPrice($price);
                    $product->setPriceBulk(null);

                    $this->entityManager->persist($product);
                    $this->entityManager->flush();
                    print_r("product with ID {$product->getId()} has been updated. Be sure to update the bulk price and mg price manually. \n");
                    dd('cum');
                }
            }
        }
    }

}