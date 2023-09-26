<?php

namespace App\Service;

use App\Entity\Product;
use App\Entity\Supplier;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\Types\Null_;
use Spekulatius\PHPScraper\PHPScraper;

class
WikiScraper
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

    public function getProducts(array $choices): array
    {
        $productsArray = [];
        foreach ($choices as $choice) {
            $choice = str_replace(' ', '_', $choice);

            $url = "https://diyhrt.cafe/index.php/{$choice}";
            $link = $this->init($url);
            $type = $link->filter('//*[@id="firstHeading"]')->text();

            $typeTitle = str_replace(" ", "_", $type);
            $link = $this->init("https://diyhrt.cafe/index.php?title={$choice}&action=edit");

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
                $priceBulk = (float)$priceBulk;

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
                    "price" => trim($price),
                    "priceBulk" => trim($priceBulk),
                    "supplierId" => $supplier->getId(),
                    "url" => $url,
                    "notes" => trim($notes),
                    "type" => $type
                ];
                $productsArray[$choice][] = $productObject;
            }
        }
        return $productsArray;
    }

    public function insertProductsIntoDatabase(array $productsArray): void
    {
        foreach ($productsArray as $type) {
            foreach ($type as $inputProduct) {
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
                    print_r("Inserting new product: {$inputProduct["name"]}\n");
                } else {
                    //updating
                    print_r("Product already exists. Updating\n");

                    $product->setName($inputProduct["name"]);
                    $product->setPrice($inputProduct["price"]);
                    $product->setPriceBulk($inputProduct["priceBulk"]);
                    $product->setSupplier($supplier);
                    $product->setUrl($inputProduct["url"]);
                    $product->setType($inputProduct["type"]);
                    $product->setNotes($inputProduct["notes"]);
                }
                $this->entityManager->flush();
            }
            $this->deleteOldFromDatabase($type);
            $this->CalculateUnits();
        }
    }

    public function scrapeSuppliers()
    {
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

    //how many layers of nesting are you on right now?
    //idk like 2 or 3 right now my dude
    //you are like a little baby, watch this
    private function deleteOldFromDatabase(array $productsArray): void
    {
        $type = $productsArray[0]['type'];
        $databaseProducts = $this->entityManager->getRepository(Product::class)->findBy(['type' => $type]);
        //dd($products, $databaseProducts);

        //comparing arrays is a resource intensive task depending on the array so seeing if they're not the same
        //size before comparing everything to save up time and resources
        if (sizeof($databaseProducts) !== sizeof($productsArray)) {
            //format arrays
            //TODO: array_map() this because this looks foul i swear im a good programmer
            foreach ($databaseProducts as $product) {
                $databaseArray[$product->getId()] = ['name' => $product->getName(), 'supplier' => $product->getSupplier()->getId()];
            }
            foreach ($productsArray as $product) {
                $productsArrayThingHelp[] = ['name' => $product['name'], 'supplier' => $product['supplierId']];
            }

            //compare
            foreach ($databaseArray as $index => $databaseProduct) {
                if (!in_array($databaseProduct, $productsArrayThingHelp)) {
                    print_r("[{$index}] deleting product: {$databaseProduct['name']}\n");
                    $deleteProduct = $this->entityManager->getRepository(Product::class)->find($index);
                    $this->entityManager->remove($deleteProduct);
                    $this->entityManager->flush();
                }
            }
        }
    }

    public function CalculateUnits()
    {
        $products = $this->entityManager->getRepository(Product::class)->findBy(['units' => Null]);
        foreach ($products as $product) {
            switch ($product->getType()) {
                case 'Finasteride':
                case 'Estradiol Pills':
                    $values = explode(' ', $product->getName());
                    $milligrams = array_slice($values, -3, 1);
                    $milligrams = preg_replace("/[^0-9.]/", "", $milligrams[0]);
                    $units = end($values);
                    $product->setUnits($milligrams * $units);
                    $this->entityManager->flush();
                    break;
                case 'Progesterone Capsules':
                case 'Estradiol Patches':
                    $values = explode(' ', $product->getName());
                    $milligrams = array_slice($values, -3, 1);
                    $milligrams = preg_replace("/[^0-9.]/", "", $milligrams[0]);
                    $units = end($values) / 100;
                    $product->setUnits($milligrams * $units);
                    $this->entityManager->flush();
                    break;
                case 'Estradiol Gel':
                    $values = explode(' ', $product->getName());
                    //number x product
                    if (preg_match('/\d+x/', $values[1])) {
                        $multiplier = preg_replace("/[^0-9.]/", "", $values[1]);
                        $milligrams = preg_replace("/[^0-9.]/", "", end($values));
                        $product->setUnits($multiplier * $milligrams);
                    } elseif (sizeof($values) === 3) {
                        $product->setUnits(preg_replace("/[^0-9.]/", "", $values[2]));
                    } else {
                        $product->setUnits(preg_replace("/[^0-9.]/", "", end($values)));
                    }
                    $this->entityManager->flush();
                    break;
                case 'Progesterone Injections':
                case 'Estradiol Injections':
                    $values = explode(' ', $product->getName());

                    if (preg_match('/^[0-9]+$/', end($values))) {
                        $multiplier = end($values);
                        $milligrams = array_slice($values, -3, 1);
                        $milligrams = explode('/', $milligrams[0]);
                        $milligrams = preg_replace("/[^0-9.]/", "", $milligrams[0]);
                        $product->setUnits($milligrams * $multiplier);
                    } else {
                        $milligrams = explode('/', end($values));
                        $milligrams = preg_replace("/[^0-9.]/", "", $milligrams[0]);
                        $product->setUnits($milligrams);
                    }
                    $this->entityManager->flush();
                    break;
                case 'Progesterone Gel':
                    $values = explode(' ', $product->getName());
                    $milligrams = preg_replace("/[^0-9.]/", "", end($values));
                    $product->setUnits($milligrams / 10);
                    $this->entityManager->flush();
                    break;
                case 'Cyproterone Acetate':
                    $values = explode(' ', $product->getName());
                    $milligrams = array_slice($values, -3, 1);
                    $units = preg_replace("/[^0-9.]/", "", $milligrams[0]) / 12.5;
                    $multiplier = end($values);
                    $product->setUnits($units * $multiplier);
                    $this->entityManager->flush();
                    break;
                case 'Bicalutamide':
                    $values = explode(' ', $product->getName());
                    $milligrams = array_slice($values, -3, 1);
                    $units = preg_replace("/[^0-9.]/", "", $milligrams[0]) / 50;
                    $multiplier = end($values);
                    $product->setUnits($units * $multiplier);
                    $this->entityManager->flush();
                    break;
                case 'Spironolactone':
                    $values = explode(' ', $product->getName());
                    $milligrams = array_slice($values, -3, 1);
                    $units = preg_replace("/[^0-9.]/", "", $milligrams[0]) / 100;
                    $multiplier = end($values);
                    $product->setUnits($units * $multiplier);
                    $this->entityManager->flush();
                    break;
                case 'Gonadotropin-Releasing Hormone Agonists':
                    $values = explode(' ', $product->getName());
                    if (preg_match('/^[0-9.]+mg$/', end($values))) {
                        $milligrams = preg_replace("/[^0-9.]/", "", end($values));
                        $product->setUnits($milligrams);
                    }
                    $this->entityManager->flush();
                    break;
                case 'Dutasteride':
                    $values = explode(' ', $product->getName());
                    $milligrams = array_slice($values, -3, 1);
                    $milligrams = preg_replace("/[^0-9.]/", "", $milligrams[0]);
                    $units = end($values) / 0.5;
                    $product->setUnits($milligrams * $units);
                    $this->entityManager->flush();
                    break;
                case 'Raloxifene':
                    $values = explode(' ', $product->getName());
                    $milligrams = array_slice($values, -3, 1);
                    $milligrams = preg_replace("/[^0-9.]/", "", $milligrams[0]);
                    $units = end($values) / 60;
                    $product->setUnits($milligrams * $units);
                    $this->entityManager->flush();
                    break;
                case 'Domperidone':
                case 'Tamoxifen':
                    $values = explode(' ', $product->getName());
                    $milligrams = array_slice($values, -3, 1);
                    $milligrams = preg_replace("/[^0-9.]/", "", $milligrams[0]);
                    $units = end($values) / 10;
                    $product->setUnits($milligrams * $units);
                    $this->entityManager->flush();
                    break;
                case 'Clomifene':
                    $values = explode(' ', $product->getName());
                    $milligrams = array_slice($values, -3, 1);
                    $milligrams = preg_replace("/[^0-9.]/", "", $milligrams[0]);
                    $units = end($values) / 50;
                    $product->setUnits($milligrams * $units);
                    $this->entityManager->flush();
                    break;
                case 'Pioglitazone':
                    $values = explode(' ', $product->getName());
                    $milligrams = array_slice($values, -3, 1);
                    $milligrams = preg_replace("/[^0-9.]/", "", $milligrams[0]);
                    $units = end($values) / 15;
                    $product->setUnits($milligrams * $units);
                    $this->entityManager->flush();
                    break;
                case 'Hydroxyprogesterone Caproate Injections':
                    $values = explode(' ', $product->getName());
                    if (preg_match('/^[0-9]+$/', end($values))) {
                        $multiplier = end($values);
                        $milligrams = array_slice($values, -3, 1);
                        $milligrams = explode('/', $milligrams[0]);
                        $milligrams = preg_replace("/[^0-9.]/", "", $milligrams[0]);
                        $product->setUnits(($milligrams * $multiplier) / 25);
                    } else {
                        $milligrams = explode('/', end($values));
                        $milligrams = preg_replace("/[^0-9.]/", "", $milligrams[0]);
                        $product->setUnits($milligrams / 25);
                    }
                    $this->entityManager->flush();
                    break;
            }
        }
        $products = $this->entityManager->getRepository(Product::class)->findBy(['units' => Null]);
        print_r(count($products) . " products left with no units: ");
        foreach ($products as $product) {
            print_r($product->getId() . ", ");
        }
        print_r("\n");
    }
}