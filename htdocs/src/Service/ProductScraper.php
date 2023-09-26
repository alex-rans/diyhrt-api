<?php

namespace App\Service;

use App\Entity\Product;
use App\Entity\Supplier;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Psr\Log\LoggerInterface;
use Spekulatius\PHPScraper\PHPScraper;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpClient\Exception\TimeoutException;

class ProductScraper
{
    private EntityManagerInterface $entityManager;
    private LoggerInterface $logger;

    /**
     * @param EntityManagerInterface $entityManager
     * @param LoggerInterface $logger
     * @param Crawler $crawler
     */
    public function __construct(EntityManagerInterface $entityManager, LoggerInterface $logger)
    {
        $this->entityManager = $entityManager;
        $this->logger = $logger;
    }

    private function init(string $url): PHPScraper
    {
        $web = new \Spekulatius\PHPScraper\PHPScraper;
        $web->setConfig(['agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:109.0) Gecko/20100101 Firefox/117.0']);
        $web->go($url);
        return $web;
    }
    public function getPriceData(array $productTypes): array{
        $updatedProducts = [];
        foreach ($productTypes as $productType){
            $products = $this->entityManager->getRepository(Product::class)->findBy(["type" => $productType]);
            foreach ($products as $product){
                print_r("[ID: {$product->getId()}]: Scraping...");

                if(!$product->getPrixeXpath() && !$product->getSupplier()->getPriceXPath()){
                    print_r("No XPath; Breaking.\n");
                    continue;
                }
                try {
                    $web = $this->init($product->getUrl());
                } catch (TimeoutException $e) {
                    print_r("Website could not be reached\n");
                    continue;
                }

                if($product->getPrixeXpath()){
                    try {
                        $price = $web->filter($product->getPrixeXpath())->text();
                    } catch (\InvalidArgumentException $e) {
                        print_r(" Price XPath could not be found; Breaking.\n");
                        continue;
                    } catch (Exception $e) {
                        print_r(" An error occurred. See logs for more information.\n");
                        continue;
                    }
                }
                else {
                    try {
                        $price = $web->filter($product->getSupplier()->getPriceXPath())->text();
                    } catch (\InvalidArgumentException $e) {
                        print_r(" Price XPath could not be found; Breaking.\n");
                        continue;
                    } catch (Exception $e) {
                        print_r(" An error occurred. See logs for more information.\n");
                        continue;
                    }
                }


                $price = preg_replace('/[^0-9.,]+/', '', $price);
                $price = str_replace(',', '.', $price);
                if (empty($product) || !$price){
                    print_r(" Price is not a number or could not be found; Breaking.\n");
                    continue;
                }
                $price = (float) $price;
                if ($price != $product->getPrice()) {
                    print_r(" Price for product {$product->getName()} has changed. Updating.");
                    $product->setPrice($price);
                    $product->setPriceBulk(null);

                    $this->entityManager->persist($product);
                    $this->entityManager->flush();
                    array_push($updatedProducts, [$product->getId() => $product->getName()]);
                }
                print_r("\n");
            }
        }
        return $updatedProducts;
    }
    public function test(int $id)
    {
//        $product = $this->entityManager->getRepository(Product::class)->find($id);
//        print_r("[ID: {$product->getId()}]: Scraping...\n");
//
//        if(!$product->getPrixeXpath() && !$product->getSupplier()->getPriceXPath()){
//            print_r("[ID: {$product->getId()}]: No XPath; Breaking.\n");
//            return;
//        }
//
//        $web = $this->init($product->getUrl());
//        $site = file_get_contents($product->getUrl());
//        $crawler = new Crawler($site);
//        dd($crawler->filterXPath($product->getPrixeXpath())->text());
//
//        if($product->getSupplier()->getPriceXPath()){
//            try {
//                $price = $web->filter($product->getSupplier()->getPriceXPath())->text();
//            } catch (\InvalidArgumentException $e) {
//                print_r("Price XPath could not be found; Breaking.\n");
//                return;
//            } catch (Exception $e) {
//                print_r("An error occurred. Check the logs for more information.\n");
//                return;
//            }
//        }
//        else {
//            try {
//                $price = $web->filterXPath($product->getPrixeXpath());
//                return $price->text();
//            } catch (\InvalidArgumentException $e) {
//                print_r("Price XPath could not be found; Breaking.\n");
//                dd($e);
//                return;
//            } catch (Exception $e) {
//                print_r("An error occurred. Check the logs for more information.\n");
//                return;
//            }
//        }
//
//
//        $price = preg_replace('/[^0-9.]+/', '', $price);
//        if (empty($product) || !$price){
//            print_r("[ID: {$product->getId()}]: Price is not a number or could not be found; Breaking.\n");
//            return;
//        }
//        $price = (float) $price;
//        if ($price != $product->getPrice()) {
//            print_r("Price for product {$product->getName()} with ID {$product->getId()} has changed. Updating...\n");
//            $product->setPrice($price);
//            $product->setPriceBulk(null);
//
//            $this->entityManager->persist($product);
//            $this->entityManager->flush();
//        }
    }

}