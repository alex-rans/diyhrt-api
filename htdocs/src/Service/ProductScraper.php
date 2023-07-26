<?php

namespace App\Service;

use App\Entity\Product;
use App\Entity\Supplier;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Psr\Log\LoggerInterface;
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
    private LoggerInterface $logger;

    /**
     * @param EntityManagerInterface $entityManager
     * @param LoggerInterface $logger
     */
    public function __construct(EntityManagerInterface $entityManager, LoggerInterface $logger)
    {
        $this->entityManager = $entityManager;
        $this->logger = $logger;
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
                print_r("[ID: {$product->getId()}]: Scraping...\n");
                if(!$product->getPrixeXpath()){
                    print_r("[ID: {$product->getId()}]: No XPath; Breaking.\n");
                    continue;
                }
                $web = $this->init($product->getUrl());


                //try getting this otherwise itll crash
                try {
                    $price = $web->filter($product->getPrixeXpath())->text();
                } catch (\InvalidArgumentException $e) {
                    print_r("[ID: {$product->getId()}]: Price XPath could not be found; Breaking.\n");
                    continue;
                } catch (Exception $e) {
                    print_r("[ID: {$product->getId()}]: An error occurred.\n");
                    continue;
                }
                $price = preg_replace('/[^0-9.]+/', '', $price);

                if (empty($product) || !$price){
                    print_r("[ID: {$product->getId()}]: Price is not a number or could not be found; Breaking.\n");
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