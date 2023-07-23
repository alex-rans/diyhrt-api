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
    public function getPriceData(array $productTypes): array{
        $updatedProducts = [];
        foreach ($productTypes as $productType){
            $products = $this->entityManager->getRepository(Product::class)->findBy(["type" => $productType]);
            foreach ($products as $product){
                if(!$product->getPrixeXpath()){
                    continue;
                }
                $web = $this->init($product->getUrl());

                //try getting this otherwise itll crash
                try {
                    $price = $web->filter($product->getPrixeXpath())->text();
                } catch (\InvalidArgumentException $e) {
                    print_r("Price XPath could not be found; Breaking.\n");
                    continue;
                }

                $price = preg_replace('/[^0-9.]+/', '', $price);

                if (empty($product) || !$price){
                    print_r("Price is not a number or could not be found. Breaking.\n");
                    continue;
                }
                $price = (float) $price;
                if ($price != $product->getPrice()) {
                    print_r("Price for product {$product->getName()} with ID {$product->getId()} has changed. Updating...\n");
                    $product->setPrice($price);
                    $product->setPriceBulk(null);

                    $this->entityManager->persist($product);
                    $this->entityManager->flush();
                    array_push($updatedProducts, [$product->getId() => $product->getName()]);
                }
            }
        }
        return $updatedProducts;
    }
    public function test(){
        $web = $this->init('https://www.google.com/');
        try {
            $price = $web->filter('//*[@id="goods_price"]')->text();
        } catch (\InvalidArgumentException $e) {
            print_r("oopsie whoopsie we did a fucky wucky");
        }
    }

}