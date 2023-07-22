<?php

namespace App\Service;

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

            $price = preg_replace('/[^0-9.]+/', '', trim($productArray[2]));
            $price = (float) $price;

            $notes = null;
            if(!empty(trim($productArray[8]))){
                $notes = $productArray[8];
            }

            $productObject = [
                "name" => trim($productArray[1]),
                "price" => $price,
                "supplierId" => $supplier->getId(),
                "url" => $url,
                "notes" => trim($notes)
            ];
            array_push($productsArray, $productObject);
        }
        return $productsArray;
    }
}