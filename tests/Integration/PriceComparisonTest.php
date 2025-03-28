<?php
namespace Tests\PriceComparison\Integration;

use PHPUnit\Framework\TestCase;
use PriceComparison\Entity\OrderItem;
use PriceComparison\Entity\Product;
use PriceComparison\Entity\Supplier;
use PriceComparison\Factory\InMemorySupplierFactory;
use PriceComparison\Service\BestOfferFinder;
use PriceComparison\Service\PriceCalculator;

class PriceComparisonTest extends TestCase
{
    private BestOfferFinder $bestOfferFinder;
    private InMemorySupplierFactory $factory;

    protected function setUp(): void
    {
        $this->factory = new InMemorySupplierFactory();
        $this->bestOfferFinder = new BestOfferFinder(
            $this->factory->createSupplierRepository(),
            new PriceCalculator()
        );
    }

    public function testExampleComparisonFromRequirements(): void
    {
        $orderItems = [
            new OrderItem('Dental Floss', 5),
            new OrderItem('Ibuprofen', 12)
        ];

        $result = $this->bestOfferFinder->findBestOffer($orderItems);

        $this->assertEquals('B', $result['best_supplier_id']);
        $this->assertEquals(102.0, $result['best_price']);
        $this->assertEquals([
            'A' => 103.0,
            'B' => 102.0
        ], $result['all_prices']);
    }

    public function testLargeQuantityDiscountScenario(): void
    {
        $orderItems = [
            new OrderItem('Ibuprofen', 105)
        ];

        $result = $this->bestOfferFinder->findBestOffer($orderItems);

        $this->assertEquals('B', $result['best_supplier_id']);
        $this->assertEquals(435.0, $result['best_price']);
        $this->assertEquals([
            'A' => 505.0,
            'B' => 435.0
        ], $result['all_prices']);
    }

    public function testUnavailableProductScenario(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage("No supplier can fulfill the entire order");

        $orderItems = [
            new OrderItem('Toothpaste', 1)
        ];

        $this->bestOfferFinder->findBestOffer($orderItems);
    }

    public function testNewSupplierWithBetterPricing(): void
    {
        $repository = $this->factory->createSupplierRepository();
        $calculator = new PriceCalculator();

        // Add a third supplier
        $supplierC = new Supplier('C');
        $supplierC->addProduct(new Product('Dental Floss', 1, 7.50));
        $supplierC->addProduct(new Product('Ibuprofen', 1, 4.50));
        $repository->addSupplier($supplierC);

        $finder = new BestOfferFinder($repository, $calculator);
        $result = $finder->findBestOffer([
            new OrderItem('Dental Floss', 1),
            new OrderItem('Ibuprofen', 1)
        ]);

        $this->assertEquals('C', $result['best_supplier_id']);
        $this->assertEquals(12.0, $result['best_price']);
    }

    public function testEmptyOrderThrowsException(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Order items cannot be empty");
        $this->bestOfferFinder->findBestOffer([]);
    }
}