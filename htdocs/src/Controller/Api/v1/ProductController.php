<?php

namespace App\Controller\Api\v1;

use App\Entity\Product;
use App\Service\IsCorrectType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Attributes as OA;
use Symfony\Component\Serializer\SerializerInterface;

class ProductController extends AbstractController
{
    #[OA\Get(
        path: '/api/v1/product',
        description: 'Retrieves an array of all products',
        tags: ['Products'],
        responses: [
            new OA\Response(response: 200,
                description: 'Successfull operation',
                content: new OA\JsonContent(schema: 'object', properties: [
                    new OA\Property(property: 'id', type: 'int', example: 1),
                    new OA\Property(property: 'name', type: 'string', example: 'Estraheal 28 x 2mg '),
                    new OA\Property(property: 'type', type: 'string', example: 'Estradiol Pills'),
                    new OA\Property(property: 'price', type: 'int', example: 10),
                    new OA\Property(property: 'priceBulk', type: 'int', example: 8.5),
                ]),
            ),
        ]
    )]
    #[Route('/api/v1/product', name: 'v1_getProducts', methods: ['GET'])]
    public function getProducts(EntityManagerInterface $entityManager, SerializerInterface $serializer): JsonResponse
    {
        $products = $entityManager->createQueryBuilder()
            ->select('p.id AS product_id',
                'p.name AS product_name',
                'p.type as product_type',
                'p.price as product_price',
                'p.priceBulk AS product_price_bulk',
                'p.notes AS product_notes',
                's.id AS supplier_id')
            ->from('App\Entity\Product', 'p')
            ->leftJoin('p.supplier', 's')
            ->getQuery()->getResult();

        $products = $serializer->serialize($products, 'json');
        return new JsonResponse($products, 200, [], true);
    }

    #[OA\Get(
        path: '/api/v1/product/type',
        description: 'Retrieves an array of all products',
        tags: ['Products'],
        parameters: [
            new OA\Parameter(name: 'type',
                description: 'The type of medication. The types can be found at /api/v1/types',
                in: 'query',
                required: true,
                schema: new OA\Schema(type: 'string'))
        ],
        responses: [
            new OA\Response(response: 200,
                description: 'Successfull operation',
                content: new OA\JsonContent(schema: 'object', properties: [
                    new OA\Property(property: 'id', type: 'int', example: 1),
                    new OA\Property(property: 'name', type: 'string', example: 'Estraheal 28 x 2mg '),
                    new OA\Property(property: 'type', type: 'string', example: 'Estradiol Pills'),
                    new OA\Property(property: 'price', type: 'int', example: 10),
                    new OA\Property(property: 'priceBulk', type: 'int', example: 8.5),
                ]),
            ),
            new OA\Response(response: 400,
                description: 'Errors',
                content: new OA\JsonContent(examples: [
                    new OA\Examples(example: '1',
                        summary: 'Parameter \'type\' was left empty',
                        description: 'Provide a parameter \'type\'. The types can be found at /api/v1/types',
                        value: 'Error 400: Parameter \'type\' was left empty'),
                    new OA\Examples(example: '2',
                        summary: 'Parameter \'type\' is incorrect',
                        description: 'Incorrect parameter \'type\'. The types can be found at /api/v1/types',
                        value: 'Error 400: The \'type\' is not correct'),
                ], schema: 'string')
            ),
        ]
    )]
    #[Route('/api/v1/product/type/', name: 'v1_getProductByType', methods: ['GET'])]
    public function getProductByType(EntityManagerInterface $entityManager,
                                     Request                $request,
                                     IsCorrectType          $isCorrectType): JsonResponse|Response
    {
        if (!$request->get('type')) {
            return new Response('Error 400: Parameter \'type\' was left empty', '400');
        }
        if (!$isCorrectType->isCorrectType($request->get('type'))) {
            return new Response('Error 400: Parameter \'type\' is incorrect', '400');
        }

        $product = $entityManager->createQueryBuilder()
            ->select('p.id AS product_id',
                'p.name AS product_name',
                'p.type as product_type',
                'p.price as product_price',
                'p.priceBulk AS product_price_bulk',
                'p.notes AS product_notes',
                's.id AS supplier_id')
            ->from('App\Entity\Product', 'p')
            ->leftJoin('p.supplier', 's')
            ->where('p.type = :type')
            ->setParameter('type', $request->get('type'));
        return $this->json($product->getQuery()->getResult());
    }

    #[OA\Get(
        path: '/api/v1/product/{id}',
        description: 'Retrieves an array of all products',
        tags: ['Products'],
        responses: [
            new OA\Response(response: 200,
                description: 'Successfull operation',
                content: new OA\JsonContent(schema: 'object', properties: [
                    new OA\Property(property: 'id', type: 'int', example: 1),
                    new OA\Property(property: 'name', type: 'string', example: 'Estraheal 28 x 2mg '),
                    new OA\Property(property: 'type', type: 'string', example: 'Estradiol Pills'),
                    new OA\Property(property: 'price', type: 'int', example: 10),
                    new OA\Property(property: 'priceBulk', type: 'int', example: 8.5),
                ]),
            ),
        ]
    )]
    #[Route('/api/v1/product/{id}', name: 'v1_getProduct', methods: ['GET'])]
    public function getProduct(EntityManagerInterface $entityManager, string $id): JsonResponse
    {
        $product = $entityManager->createQueryBuilder()
            ->select('p.id AS product_id',
                'p.name AS product_name',
                'p.type as product_type',
                'p.price as product_price',
                'p.priceBulk AS product_price_bulk',
                'p.notes AS product_notes',
                's.id AS supplier_id')
            ->from('App\Entity\Product', 'p')
            ->where('p.id = :id')
            ->leftJoin('p.supplier', 's')
            ->setParameter('id', $id);
        return $this->json($product->getQuery()->getResult());
    }


    #[OA\Post(
        path: '/api/v1/product/',
        description: 'Creates a new product. Reserved for authorized users only',
        tags: ['Products'],
        parameters: [
            new OA\Parameter(name: 'X-AUTH-TOKEN',
                description: 'Authentication token',
                in: 'header',
                required: true,
                schema: new OA\Schema(type: 'string')),
            new OA\Parameter(name: 'name',
                description: 'Name for new product',
                in: 'query',
                required: true,
                schema: new OA\Schema(type: 'string')),
            new OA\Parameter(name: 'url',
                description: 'Link to product page',
                in: 'query',
                required: true,
                schema: new OA\Schema(type: 'string')),
            new OA\Parameter(name: 'type',
                description: 'Product type',
                in: 'query',
                required: true,
                schema: new OA\Schema(type: 'string')),
            new OA\Parameter(name: 'price',
                description: 'Price for new product',
                in: 'query',
                schema: new OA\Schema(type: 'int')),
            new OA\Parameter(name: 'priceXpath',
                description: 'Xpath to css selector of the price',
                in: 'query',
                schema: new OA\Schema(type: 'string')),
        ],
        responses: [
            new OA\Response(response: 200,
                description: 'Successfull operation',
                content: new OA\JsonContent(schema: 'object', properties: [
                    new OA\Property(property: 'id', type: 'int', example: 1),
                    new OA\Property(property: 'name', type: 'string', example: 'Estraheal 28 x 2mg '),
                    new OA\Property(property: 'type', type: 'string', example: 'Estradiol Pills'),
                    new OA\Property(property: 'price', type: 'int', example: 10),
                    new OA\Property(property: 'priceBulk', type: 'int', example: 8.5),
                ]),
            ),
            new OA\Response(response: 400,
                description: 'Errors',
                content: new OA\JsonContent(examples: [
                    new OA\Examples(example: '1',
                        summary: 'Parameter \'type\' is incorrect',
                        description: 'Incorrect parameter \'type\'. The types can be found at /api/v1/types',
                        value: 'Error 400: The \'type\' is not correct'),
                ], schema: 'string')
            ),
        ]
    )]
    #[Route('/api/v1/product/', name: 'v1_insertProduct', methods: ['POST'])]
    public function insertProduct(EntityManagerInterface $entityManager,
                                  Request                $request, IsCorrectType $isCorrectType): JsonResponse|Response
    {
        if ($isCorrectType->isCorrectType($request->get('type'))) {
            return new Response('Error 400: The type is not correct', '400');
        }

        $name = $request->get('name');
        $url = $request->get('url');
        $priceXpath = $request->get('priceXpath') ?? null;
        $type = $request->get('type');
        $price = $request->get('price') ?? null;
        $priceBulk = $request->get('priceBulk') ?? null;

        $product = new Product();
        $product->setName($name);
        $product->setUrl($url);
        $product->setPriceXpath($priceXpath);
        $product->setPrice($price);
        $product->setPriceBulk($priceBulk);
        $product->setType($type);

        $entityManager->persist($product);
        $entityManager->flush();
        return $this->json($product);
    }

    #[OA\Post(
        path: '/api/v1/product/',
        description: 'Creates a new product. Reserved for authorized users only',
        tags: ['Products'],
        parameters: [
            new OA\Parameter(name: 'X-AUTH-TOKEN',
                description: 'Authentication token',
                in: 'header',
                schema: new OA\Schema(type: 'string')),
            new OA\Parameter(name: 'name',
                description: 'Name for new product',
                in: 'query',
                schema: new OA\Schema(type: 'string')),
            new OA\Parameter(name: 'url',
                description: 'Link to product page',
                in: 'query',
                schema: new OA\Schema(type: 'string')),
            new OA\Parameter(name: 'type',
                description: 'Product type',
                in: 'query',
                schema: new OA\Schema(type: 'string')),
            new OA\Parameter(name: 'price',
                description: 'Price for new product',
                in: 'query',
                schema: new OA\Schema(type: 'int')),
            new OA\Parameter(name: 'priceXpath',
                description: 'Xpath to css selector of the price',
                in: 'query',
                schema: new OA\Schema(type: 'string')),
        ],
        responses: [
            new OA\Response(response: 200,
                description: 'Successfull operation',
                content: new OA\JsonContent(schema: 'object', properties: [
                    new OA\Property(property: 'id', type: 'int', example: 1),
                    new OA\Property(property: 'name', type: 'string', example: 'Estraheal 28 x 2mg '),
                    new OA\Property(property: 'type', type: 'string', example: 'Estradiol Pills'),
                    new OA\Property(property: 'price', type: 'int', example: 10),
                    new OA\Property(property: 'priceBulk', type: 'int', example: 8.5),
                ]),
            ),
            new OA\Response(response: 400,
                description: 'Errors',
                content: new OA\JsonContent(examples: [
                    new OA\Examples(example: '1',
                        summary: 'Parameter \'type\' is incorrect',
                        description: 'Incorrect parameter \'type\'. The types can be found at /api/v1/types',
                        value: 'Error 400: The \'type\' is not correct'),
                ], schema: 'string')
            ),
        ]
    )]
    #[OA\Post(
        path: '/api/v1/product/{id}',
        description: 'Updates a product with new values',
        tags: ['Products'],
        parameters: [
            new OA\Parameter(name: 'X-AUTH-TOKEN',
                description: 'Authentication token',
                in: 'header',
                required: true,
                schema: new OA\Schema(type: 'string')),
            new OA\Parameter(name: 'name',
                description: 'Name for new product',
                in: 'query',
                schema: new OA\Schema(type: 'string')),
            new OA\Parameter(name: 'url',
                description: 'Link to product page',
                in: 'query',
                schema: new OA\Schema(type: 'string')),
            new OA\Parameter(name: 'type',
                description: 'Product type',
                in: 'query',
                schema: new OA\Schema(type: 'string')),
            new OA\Parameter(name: 'price',
                description: 'Price for new product',
                in: 'query',
                schema: new OA\Schema(type: 'int')),
            new OA\Parameter(name: 'priceXpath',
                description: 'Xpath to css selector of the price',
                in: 'query',
                schema: new OA\Schema(type: 'string')),
        ],
        responses: [
            new OA\Response(response: 200,
                description: 'Successfull operation',
                content: new OA\JsonContent(schema: 'object', properties: [
                    new OA\Property(property: 'id', type: 'int', example: 1),
                    new OA\Property(property: 'name', type: 'string', example: 'Estraheal 28 x 2mg '),
                    new OA\Property(property: 'type', type: 'string', example: 'Estradiol Pills'),
                    new OA\Property(property: 'price', type: 'int', example: 10),
                    new OA\Property(property: 'priceBulk', type: 'int', example: 8.5),
                ]),
            ),
            new OA\Response(response: 400,
                description: 'Errors',
                content: new OA\JsonContent(examples: [
                    new OA\Examples(example: '1',
                        summary: 'Parameter \'type\' is incorrect',
                        description: 'Incorrect parameter \'type\'. The types can be found at /api/v1/types',
                        value: 'Error 400: The \'type\' is not correct'),
                ], schema: 'string')
            ),
        ]
    )]
    #[Route('/api/v1/product/{id}', name: 'v1_updateProduct', methods: ['POST'])]
    public function updateProduct(EntityManagerInterface $entityManager,
                                  Request                $request, IsCorrectType $isCorrectType,
                                                         $id): JsonResponse|Response
    {
        $product = $entityManager->getRepository(Product::class)->find($id);

        if ($request->get('type')) {
            if (!$isCorrectType->isCorrectType($request->get('type'))) {
                return new Response('Error 400: The type is not correct', '400');
            }
            $product->setType($request->get('type'));
        }

        //this seems stupid but idk another way
        if ($request->get('name')) {
            $product->setName($request->get('name'));
        }

        if ($request->get('url')) {
            $product->setUrl($request->get('url'));
        }

        if ($request->get('priceXpath')) {
            $product->setXpath($request->get('priceXpath'));
        }

        if ($request->get('price')) {
            $product->setPrice($request->get('price'));
        }

        if ($request->get('priceBulk')) {
            $product->setPriceBulk($request->get('priceBulk'));
        }

        $entityManager->persist($product);
        $entityManager->flush();
        return $this->json($product);
    }

    #[OA\Delete(
        path: '/api/v1/product/{id}',
        description: 'Delete a product with a given ID',
        tags: ['Products'],
        parameters: [
            new OA\Parameter(name: 'X-AUTH-TOKEN',
                description: 'Authentication token',
                in: 'header',
                required: true,
                schema: new OA\Schema(type: 'string')),
        ],
        responses: [
            new OA\Response(response: 200,
                description: 'Successfull operation',
                content: new OA\JsonContent(schema: 'object', properties: [
                    new OA\Property(property: 'id', type: 'int', example: 1),
                    new OA\Property(property: 'name', type: 'string', example: 'Estraheal 28 x 2mg '),
                    new OA\Property(property: 'type', type: 'string', example: 'Estradiol Pills'),
                    new OA\Property(property: 'price', type: 'int', example: 10),
                    new OA\Property(property: 'priceBulk', type: 'int', example: 8.5),
                ]),
            ),
        ]
    )]
    #[Route('/api/v1/product/{id}', name: 'v1_deleteProduct', methods: ['DELETE'])]
    public function deleteProduct(EntityManagerInterface $entityManager, $id): JsonResponse
    {
        $product = $entityManager->getRepository(Product::class)->find($id);
        $entityManager->remove($product);
        $entityManager->flush();
        return $this->json('product deleted');
    }

    #[OA\Get(
        path: '/api/v1/types',
        description: 'Returns an array of valid product types',
        tags: ['types'],
        responses: [
            new OA\Response(response: 200,
                description: 'Successfull operation',
            ),
        ]
    )]
    #[Route('/api/v1/types', name: 'v1_getTypes', methods: ['GET'])]
    public function getTypes(): JsonResponse
    {
        $types = [
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
            "GnRH Agonists",
            "Finasteride",
            "Dutasteride",
            "Raloxifene",
            "Tamoxifen",
            "Clomifene",
            "Domperidone",
            "Pioglitazone",
            "HydroxyProg Injections",
        ];
        return $this->json($types);
    }


}
