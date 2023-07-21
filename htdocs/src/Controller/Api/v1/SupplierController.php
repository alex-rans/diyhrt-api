<?php

namespace App\Controller\Api\v1;

use App\Entity\Product;
use App\Entity\Supplier;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Attributes as OA;


class SupplierController extends AbstractController
{
    #[Route('/api/v1/supplier/', name: 'v1_getSuppliers', methods: ['GET'])]
    public function getSuppliers(EntityManagerInterface $entityManager): JsonResponse
    {
        $suppliers = $entityManager->createQueryBuilder()
            ->select('s.id AS supplier_id',
                's.name AS supplier_name',
                's.paymentMethods as suplier_paymentmethods',
                's.url AS supplier_url',
                's.notes AS supplier_notes')
            ->from('App\Entity\Supplier', 's')
            ->getQuery()->getResult();
        return $this->json($suppliers);
    }

    #[Route('/api/v1/supplier/{id}', name: 'v1_getSupplier', methods: ['GET'])]
    public function getSupplier(EntityManagerInterface $entityManager, $id): JsonResponse
    {
        $suppliers = $entityManager->createQueryBuilder()
            ->select('s.id AS supplier_id',
                's.name AS supplier_name',
                's.paymentMethods as suplier_paymentmethods',
                's.url AS supplier_url',
                's.notes AS supplier_notes')
            ->from('App\Entity\Supplier', 's')
            ->where('s.id = :id')
            ->setParameter('id', $id)
            ->getQuery()->getResult();
        return $this->json($suppliers);
    }
    #[Route('/api/v1/supplier/{id}/products', name: 'v1_getSupplierProducts', methods: ['GET'])]
    public function getSupplierProducts(EntityManagerInterface $entityManager, $id): JsonResponse
    {
        $product = $entityManager->createQueryBuilder()
            ->select('p.id AS product_id',
                'p.name AS product_name',
                'p.type as product_type',
                'p.price as product_price',
                'p.priceBulk AS product_price_bulk',
                'p.notes AS product_notes')
            ->from('App\Entity\Product', 'p')
            ->leftJoin('p.supplier', 's')
            ->where('s.id = :id')
            ->setParameter('id', $id)
            ->getQuery()->getResult();
        return $this->json($product);
    }

    #[Route('/api/v1/supplier/', name: 'v1_insertSupplier', methods: ['POST'])]
    public function insertSupplier(EntityManagerInterface $entityManager, Request $request): JsonResponse|Response
    {
        $name = $request->get('name');
        $shipping = $request->get('shipping');
        $paymentMethods = $request->get('paymentMethods');
        $notes = $request->get('notes');
        $url = $request->get('url');

        if (!$name || !$shipping || !$paymentMethods || !$url) {
            return new Response('Error 400: You didnt fill in all the required fields', '400');
        }
        if (!is_array($paymentMethods)) {
            return new Response('Error 400: field paymentMethods is not an array', '400');

        }

        $supplier = new Supplier();
        $supplier->setName($name);
        $supplier->setShipping($shipping);
        $supplier->setPaymentMethods($paymentMethods);
        $supplier->setNotes($notes);
        $supplier->setUrl($url);

        $entityManager->persist($supplier);
        $entityManager->flush();

        return $this->json($supplier);
    }

    #[Route('/api/v1/supplier/{id}', name: 'v1_updateSupplier', methods: ['POST'])]
    public function updateSupplier(EntityManagerInterface $entityManager, Request $request, $id): JsonResponse|Response
    {
        $supplier = $entityManager->getRepository(Supplier::class)->find($id);

        if ($request->get('name')) {
            $supplier->setName($request->get('name'));
        }

        if ($request->get('url')) {
            $supplier->setUrl($request->get('url'));
        }

        if ($request->get('shipping')) {
            $supplier->setShipping($request->get('shipping'));
        }

        if ($request->get('paymentMethods')) {
            if (!is_array($request->get('paymentMethods'))) {
                return new Response('Error 400: field paymentMethods is not an array', '400');
            }
            $supplier->setPaymentMethods($request->get('paymentMethods'));
        }

        if ($request->get('notes')) {
            $supplier->setNotes($request->get('notes'));
        }

        $entityManager->persist($supplier);
        $entityManager->flush();

        return new JsonResponse($supplier);
    }

    #[Route('/api/v1/supplier/{id}', name: 'v1_deleteSupplier', methods: ['DELETE'])]
    public function deleteSupplier(EntityManagerInterface $entityManager, $id): JsonResponse
    {
        $supplier = $entityManager->getRepository(Supplier::class)->find($id);
        $entityManager->remove($supplier);
        $entityManager->flush();
        return new JsonResponse('Supplier Removed');
    }
}
