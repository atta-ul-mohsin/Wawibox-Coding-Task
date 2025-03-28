<?php
namespace Tests\PriceComparison\Unit\Service;

use PHPUnit\Framework\TestCase;
use PriceComparison\Entity\OrderItem;
use PriceComparison\Entity\Product;
use PriceComparison\Entity\Supplier;
use PriceComparison\Factory\InMemorySupplierFactory;
use PriceComparison\Service\BestOfferFinder;
use PriceComparison\Service\PriceCalculator;

class BestOfferFinderTest extends TestCase
{
    private BestOfferFinder $finder;
    private InMemorySupplierFactory $factory;

    protected function setUp(): void
    {
        $this->factory = new InMemorySupplierFactory();
        $this->finder = new BestOfferFinder(
            $this->factory->createSupplierRepository(),
            new PriceCalculator()
        );
    }

    public function testFindBestOfferWithSingleSupplier(): void
    {
        // Create custom repository
        $repository = $this->factory->createSupplierRepository();
        $supplier = new Supplier('A');
        $supplier->addProduct(new Product('Floss', 1, 10.00));
        $repository->addSupplier($supplier);

        $finder = new BestOfferFinder($repository, new PriceCalculator());

        $result = $finder->findBestOffer([
            new OrderItem('Floss', 2)
        ]);

        $this->assertEquals('A', $result['best_supplier_id']);
        $this->assertEquals(20.00, $result['best_price']);
    }

    public function testFindBestOfferWithMultipleSuppliers(): void
    {
        $repository = $this->factory->createSupplierRepository();

        // Supplier A: 10 per unit
        $supplierA = new Supplier('A');
        $supplierA->addProduct(new Product('Floss', 1, 10.00));
        $repository->addSupplier($supplierA);

        // Supplier B: 8 per unit
        $supplierB = new Supplier('B');
        $supplierB->addProduct(new Product('Floss', 1, 8.00));
        $repository->addSupplier($supplierB);

        $finder = new BestOfferFinder($repository, new PriceCalculator());

        $result = $finder->findBestOffer([
            new OrderItem('Floss', 5)
        ]);

        $this->assertEquals('B', $result['best_supplier_id']);
        $this->assertEquals(40.00, $result['best_price']);
    }

    public function testUnfulfillableOrderThrowsException(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('No supplier can fulfill the entire order');
        $this->finder->findBestOffer([
            new OrderItem('Nonexistent', 1)
        ]);
    }

    public function testEmptyOrderThrowsException(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Order items cannot be empty');
        $this->finder->findBestOffer([]);
    }

    public function testComplexOrderWithPackageCombinations(): void
    {
        $repository = $this->factory->createSupplierRepository();

        $supplierA = new Supplier('A');
        $supplierA->addProduct(new Product('Floss', 20, 160.00));
        $supplierA->addProduct(new Product('Floss', 1, 9.00));
        $repository->addSupplier($supplierA);

        $supplierB = new Supplier('B');
        $supplierB->addProduct(new Product('Floss', 10, 71.00));
        $supplierB->addProduct(new Product('Floss', 1, 8.00));
        $repository->addSupplier($supplierB);

        $finder = new BestOfferFinder($repository, new PriceCalculator());

        $result = $finder->findBestOffer([
            new OrderItem('Floss', 25)
        ]);

        $this->assertEquals('B', $result['best_supplier_id']);
        $this->assertEquals(182.00, $result['best_price']);
    }

    public function testFactoryConfiguration(): void
    {
        // Test that factory setup works
        $result = $this->finder->findBestOffer([
            new OrderItem('Dental Floss', 5),
            new OrderItem('Ibuprofen', 12)
        ]);

        $this->assertEquals('B', $result['best_supplier_id']);
        $this->assertEquals(102.0, $result['best_price']);
    }
}