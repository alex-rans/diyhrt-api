<?php

namespace App\Service;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;

class GenerateService
{
    private EntityManagerInterface $entityManager;

    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function GenerateTable(array $type): string
    {

        $table = '';
        $products = $this->entityManager->getRepository(Product::class)->findBy(['type' => $type[0]]);
        //this is fucking stupid and there has to be a better way to generate this but currently I am braindead.
        foreach ($products as $product) {
            $table .= "|" . $product->getName() . "\n";
            $table .= "|" . $this->GetCurrency($product->getSupplier()->getCurrency()) . $product->getPrice() . "\n";
            if($product->getPriceBulk()) {
                $table .= "|" . $this->GetCurrency($product->getSupplier()->getCurrency()) . $product->getPriceBulk() . "\n";
            }
            else {
                $table .= "|Update bulk price\n";
            }
            if ($product->getUnits()) {
                $table .= "|" . $this->GetCurrency($product->getSupplier()->getCurrency()) . round($product->getPrice() / $product->getUnits(), 2) . "\n";
                $table .= "|" . $this->GetCurrency($product->getSupplier()->getCurrency()) . round($product->getPriceBulk() / $product->getUnits(), 2) . "\n";
            }
            else {
                $table .= "|update units in db\n";
                $table .= "|update units in db\n";
            }

            $table .= "|[" . $product->getUrl() . " " . $product->getSupplier()->getName() . "]\n";
            $table .= "|" . $product->getSupplier()->getShipping() . "\n";
            $table .= "|" . $product->getNotes() . "\n";
            $table .= "|-\n";
        }
        $table = substr($table, 0, strrpos($table, "|-\n"));
        $table .= "|}\n";

        return $table;
    }

    private function GetCurrency(string $currency): string
    {
        $currencyArray = [
            "USD" => "$",
            "EUR" => "€",
            "GBP" => "£"
        ];
        return $currencyArray[$currency];
    }

}