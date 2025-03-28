<?php
namespace Tests\PriceComparison\Unit\Repository;

use PHPUnit\Framework\TestCase;
use PriceComparison\Entity\Product;
use PriceComparison\Entity\Supplier;
use PriceComparison\Repository\InMemorySupplierRepository;

class InMemorySupplierRepositoryTest extends TestCase
{
    public function testAddAndFindSuppliers()
    {
        $repo = new InMemorySupplierRepository();
        $supplier = new Supplier('A');
        $supplier->addProduct(new Product('Floss', 1, 9.99));

        $repo->addSupplier($supplier);

        // Get the first supplier from the repository
        $suppliers = $repo->findAll();
        $firstSupplier = $suppliers['A'] ?? null; // Access by ID

        $this->assertCount(1, $suppliers);
        $this->assertNotNull($firstSupplier);
        $this->assertEquals('A', $firstSupplier->getId());
    }

    public function testFindAllReturnsEmptyArrayInitially()
    {
        $repo = new InMemorySupplierRepository();
        $this->assertEmpty($repo->findAll());
    }

    public function testAddMultipleSuppliers()
    {
        $repo = new InMemorySupplierRepository();

        $supplier1 = new Supplier('A');
        $supplier2 = new Supplier('B');

        $repo->addSupplier($supplier1);
        $repo->addSupplier($supplier2);

        $suppliers = $repo->findAll();

        $this->assertCount(2, $suppliers);
        $this->assertArrayHasKey('A', $suppliers);
        $this->assertArrayHasKey('B', $suppliers);
    }
}