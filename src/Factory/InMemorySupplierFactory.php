<?php

namespace PriceComparison\Factory;

use PriceComparison\Entity\Product;
use PriceComparison\Entity\Supplier;
use PriceComparison\Repository\InMemorySupplierRepository;
use PriceComparison\Service\BestOfferFinder;
use PriceComparison\Service\PriceCalculator;

class InMemorySupplierFactory implements SupplierFactoryInterface
{
    public function createSupplierRepository(): InMemorySupplierRepository
    {
        $repository = new InMemorySupplierRepository();

        // Supplier A
        $supplierA = new Supplier('A');
        $supplierA->addProduct(new Product('Dental Floss', 1, 9.00));
        $supplierA->addProduct(new Product('Dental Floss', 20, 160.00));
        $supplierA->addProduct(new Product('Ibuprofen', 1, 5.00));
        $supplierA->addProduct(new Product('Ibuprofen', 10, 48.00));
        $repository->addSupplier($supplierA);

        // Supplier B
        $supplierB = new Supplier('B');
        $supplierB->addProduct(new Product('Dental Floss', 1, 8.00));
        $supplierB->addProduct(new Product('Dental Floss', 10, 71.00));
        $supplierB->addProduct(new Product('Ibuprofen', 1, 6.00));
        $supplierB->addProduct(new Product('Ibuprofen', 5, 25.00));
        $supplierB->addProduct(new Product('Ibuprofen', 100, 410.00));
        $repository->addSupplier($supplierB);

        return $repository;
    }

    public static function createBestOfferFinder(): BestOfferFinder
    {
        $repository = self::createSupplierRepository();
        return new BestOfferFinder($repository, new PriceCalculator());
    }
}