<?php

namespace App\Controller\Api\v1;

use App\Entity\Product;
use App\Service\Types;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class ProductController extends AbstractController
{
    #[Route('/api/v1/product', name: 'v1_getProducts', methods: ['GET'])]
    public function getProducts(EntityManagerInterface $entityManager,
                                SerializerInterface    $serializer,
                                Request                $request,
                                Types                  $types): JsonResponse|Response
    {

        if ($request->get('type')) {
            if (!$types->isCorrectType($request->get('type'))) {
                return new Response('Error 400: Parameter \'type\' is incorrect', '400');
            }
            $product = $entityManager->createQueryBuilder()
                ->select('p.id AS product_id',
                    'p.name AS product_name',
                    'p.type as product_type',
                    'p.price as product_price',
                    'p.priceBulk AS product_price_bulk',
                    'p.notes AS product_notes',
                    'p.url AS product_link',
                    's.id AS supplier_id')
                ->from('App\Entity\Product', 'p')
                ->leftJoin('p.supplier', 's')
                ->where('p.type = :type')
                ->setParameter('type', $request->get('type'));
            return $this->json($product->getQuery()->getResult());
        }
        $products = $entityManager->createQueryBuilder()
            ->select('p.id AS product_id',
                'p.name AS product_name',
                'p.type as product_type',
                'p.price as product_price',
                'p.priceBulk AS product_price_bulk',
                'p.notes AS product_notes',
                'p.url AS product_link',
                's.id AS supplier_id')
            ->from('App\Entity\Product', 'p')
            ->leftJoin('p.supplier', 's')
            ->getQuery()->getResult();

        $products = $serializer->serialize($products, 'json');
        return new JsonResponse($products, 200, [], true);
    }

    #[Route('/api/v1/product/types', name: 'v1_getTypes', methods: ['GET'])]
    public function getTypes(Types $types): JsonResponse
    {
        return $this->json($types->getChoices());
    }

    #[Route('/api/v1/product/{id}', name: 'v1_getProduct', methods: ['GET'])]
    public function getProduct(EntityManagerInterface $entityManager, string $id): JsonResponse|Response
    {
        $product = $entityManager->createQueryBuilder()
            ->select('p.id AS product_id',
                'p.name AS product_name',
                'p.type as product_type',
                'p.price as product_price',
                'p.priceBulk AS product_price_bulk',
                'p.notes AS product_notes',
                'p.url AS product_link',
                's.id AS supplier_id')
            ->from('App\Entity\Product', 'p')
            ->where('p.id = :id')
            ->leftJoin('p.supplier', 's')
            ->setParameter('id', $id);

        $product = $product->getQuery()->getResult();

        if (empty($product)) {
            return new Response('Error 404: Product not found', '404');
        }
        return $this->json($product);
    }

    #[Route('/api/v1/product/', name: 'v1_insertProduct', methods: ['POST'])]
    public function insertProduct(EntityManagerInterface $entityManager,
                                  Request                $request, Types $types): JsonResponse|Response
    {
        $name = $request->get('name');
        $url = $request->get('url');
        $priceXpath = $request->get('priceXpath') ?? null;
        $type = $request->get('type');
        $price = $request->get('price') ?? null;
        $priceBulk = $request->get('priceBulk') ?? null;
        $notes = $request->get('notes') ?? null;
        $supplierId = $request->get('supplierId');

        if (!$name || !$type || !$url || !$supplierId) {
            return new Response('Error 400: Missing required fields', '400');
        }
        if ($types->isCorrectType($request->get('type'))) {
            return new Response('Error 400: The type is not correct', '400');
        }

        $product = new Product();
        $product->setName($name);
        $product->setUrl($url);
        $product->setPrixeXpath($priceXpath);
        $product->setPrice($price);
        $product->setPriceBulk($priceBulk);
        $product->setType($type);
        $product->setSupplier($supplierId);
        $product->setNotes($notes);

        $entityManager->persist($product);
        $entityManager->flush();
        return $this->json($product);
    }

    #[Route('/api/v1/product/{id}', name: 'v1_updateProduct', methods: ['POST'])]
    public function updateProduct(EntityManagerInterface $entityManager,
                                  Request                $request, Types $types,
                                                         $id): JsonResponse|Response
    {
        $product = $entityManager->getRepository(Product::class)->find($id);

        if(!$product) {
            return new Response('Error 404: Product not found', '404');
        }

        $name = $request->get('name');
        $url = $request->get('url');
        $priceXpath = $request->get('priceXpath') ?? null;
        $type = $request->get('type');
        $price = $request->get('price') ?? null;
        $priceBulk = $request->get('priceBulk') ?? null;
        $notes = $request->get('notes') ?? null;
        $supplierId = $request->get('supplierId');

        if (!$types->isCorrectType($request->get('type'))) {
            return new Response('Error 400: The type is not correct', '400');
        }

        //this seems stupid but idk another way
        if ($name) {
            $product->setName($name);
        }
        if ($url) {
            $product->setUrl($url);
        }
        if ($type){
            $product->setType($type);
        }
        if ($notes) {
            $product->setNotes($notes);
        }
        if ($priceXpath) {
            $product->setXpath($priceXpath);
        }
        if ($price) {
            $product->setPrice($price);
        }
        if ($priceBulk) {
            $product->setPriceBulk($priceBulk);
        }
        if ($supplierId) {
            $product->setSupplier($supplierId);
        }

        $entityManager->persist($product);
        $entityManager->flush();
        return $this->json($product);
    }

    #[Route('/api/v1/product/{id}', name: 'v1_deleteProduct', methods: ['DELETE'])]
    public function deleteProduct(EntityManagerInterface $entityManager, $id): JsonResponse|Response
    {
        $product = $entityManager->getRepository(Product::class)->find($id);
        if(!$product) {
            return new Response('Error 404: Product not found', '400');
        }
        $entityManager->remove($product);
        $entityManager->flush();
        return $this->json('product deleted');
    }


}
