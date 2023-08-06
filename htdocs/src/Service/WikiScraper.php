<?php

namespace App\Service;

use App\Entity\Product;
use App\Entity\Supplier;
use Doctrine\ORM\EntityManagerInterface;
use Spekulatius\PHPScraper\PHPScraper;

class WikiScraper
{
    private EntityManagerInterface $entityManager;

    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    private function init(string $url): PHPScraper
    {
        $web = new \Spekulatius\PHPScraper\PHPScraper;
        $web->go($url);
        return $web;
    }

    public function getProducts(string $url): array
    {
        $productsArray = [];
        $link = $this->init($url);
        $type = $link->filter('//*[@id="firstHeading"]')->text();

        $typeTitle = str_replace(" ", "_", $type);
        $link = $this->init("https://diyhrt.cafe/index.php?title={$typeTitle}&action=edit");

        $text = $link->filter('//*[@id="wpTextbox1"]')->text();
        $textArray = explode("|-", $text);
        $textArray = array_slice($textArray, 1);

        foreach ($textArray as $product) {
            $productArray = explode("|", $product);

            //get supplier
            $supplierArray = str_replace(['[', ']'], '', $productArray[6]);
            $supplierArray = explode(" ", $supplierArray);
            $url = $supplierArray[0];
            array_shift($supplierArray);
            $supplier = trim(implode(" ", $supplierArray));
            $supplier = $this->entityManager->getRepository(Supplier::class)->findOneBy(['name' => $supplier]);

            //price text to float
            $price = preg_replace('/[^0-9.]+/', '', trim($productArray[2]));
            $price = (float)$price;

            //price bulk
            $priceBulk = preg_replace('/[^0-9.]+/', '', trim($productArray[3]));
            $priceBulk = (float) $priceBulk;

            //notes
            $notes = null;
            if (!empty(trim($productArray[8]))) {
                $notes = trim($productArray[8]);
            }
            if (!$supplier) {
                print_r("Supplier not found in DB. Breaking \n");
                continue;
            }

            $productObject = [
                "name" => trim($productArray[1]),
                "price" => $price,
                "priceBulk" => $priceBulk,
                "supplierId" => $supplier->getId(),
                "url" => $url,
                "notes" => $notes,
                "type" => $type
            ];
            array_push($productsArray, $productObject);
        }
        return $productsArray;
    }

    public function insertProductsIntoDatabase(array $productsArray): void
    {
        foreach ($productsArray as $inputProduct) {
            $supplier = $this->entityManager->getRepository(Supplier::class)->find($inputProduct["supplierId"]);

            $product = $this->entityManager->getRepository(Product::class)->findOneBy([
                'name' => $inputProduct["name"],
                'supplier' => $supplier
            ]);
            if (!$product) {
                $product = new Product();
                $product->setName($inputProduct["name"]);
                $product->setPrice($inputProduct["price"]);
                $product->setPriceBulk($inputProduct["priceBulk"]);
                $product->setSupplier($supplier);
                $product->setUrl($inputProduct["url"]);
                $product->setType($inputProduct["type"]);
                $product->setNotes($inputProduct["notes"]);

                $this->entityManager->persist($product);
            } else {
                //updating
                print_r("Product already exists. Updating\n");

                $product->setName($inputProduct["name"]);
                $product->setPrice($inputProduct["price"]);
                $product->setPriceBulk($inputProduct["priceBulk"]);
                $product->setSupplier($supplier);
                $product->setType($inputProduct["type"]);
                $product->setNotes($inputProduct["notes"]);
            }
            $this->entityManager->flush();
        }
    }
    public function scrapeSuppliers(){
        //TODO: Kind of ass to scrape rn because of table layout. Will finish later
        print_r("Not implemented yet\n");
        return
        $web = $this->init('https://diyhrt.cafe/index.php?title=Main_Page&action=edit');
        $text = $web->filter('//*[@id="wpTextbox1"]')->text();
        $textArray = explode("|+", $text);
        $suppliersArray = $textArray[1];
        $suppliersArray = explode("|-", $suppliersArray);
        array_shift($suppliersArray);
        foreach ($suppliersArray as $supplier) {
            $supplierArray = explode("|", $supplier);

            //get supplier name and url
            $supplierNameArray = str_replace(['[', ']'], '', $supplierArray[1]);
            $supplierNameArray = explode(" ", $supplierNameArray);
            dd($supplierNameArray);
        }
        dd($suppliersArray);
    }
}