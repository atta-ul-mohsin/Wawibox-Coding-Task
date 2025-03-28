<?php
namespace PriceComparison\Repository;

use PriceComparison\Entity\Supplier;

class InMemorySupplierRepository implements SupplierRepositoryInterface {
    /** @var Supplier[] */
    private array $suppliers;

    public function __construct(array $suppliers = []) {
        $this->suppliers = $suppliers;
    }

    /**
     * @param Supplier $supplier
     * @return void
     */
    public function addSupplier(Supplier $supplier): void {
        $this->suppliers[$supplier->getId()] = $supplier;
    }

    /**
     * @return array|Supplier[]
     */
    public function findAll(): array {
        return $this->suppliers;
    }
}