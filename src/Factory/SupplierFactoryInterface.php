<?php
namespace PriceComparison\Factory;

use PriceComparison\Repository\SupplierRepositoryInterface;

interface SupplierFactoryInterface
{
    /**
     * @return SupplierRepositoryInterface
     */
    public function createSupplierRepository(): SupplierRepositoryInterface;
}