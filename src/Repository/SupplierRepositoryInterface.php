<?php
namespace PriceComparison\Repository;

use PriceComparison\Entity\Supplier;

interface SupplierRepositoryInterface
{
    /** @return Supplier[] */
    public function findAll(): array;
}