<?php
namespace Tests\PriceComparison\Unit\Entity;

use PHPUnit\Framework\TestCase;
use PriceComparison\Entity\Product;
use PriceComparison\Entity\Supplier;

class SupplierTest extends TestCase
{
    private Supplier $supplier;

    protected function setUp(): void
    {
        $this->supplier = new Supplier('A');
    }

    public function testInitialState()
    {
        $this->assertEquals('A', $this->supplier->getId());
        $this->assertEmpty($this->supplier->getPricingForProduct('Floss'));
    }

    public function testAddAndRetrieveProducts()
    {
        $product1 = new Product('Floss', 1, 9.00);
        $product2 = new Product('Floss', 20, 160.00);

        $this->supplier->addProduct($product1);
        $this->supplier->addProduct($product2);

        $prices = $this->supplier->getPricingForProduct('Floss');
        $this->assertCount(2, $prices);
        $this->assertEquals(160.00, $prices[20]);
        $this->assertEquals(9.00, $prices[1]);
    }

    public function testCanFulfillProduct()
    {
        $this->supplier->addProduct(new Product('Floss', 1, 9.00));
        $this->assertTrue($this->supplier->canSupplyProduct('Floss'));
        $this->assertFalse($this->supplier->canSupplyProduct('Ibuprofen'));
    }

    public function testCanFulfillQuantity()
    {
        $this->supplier->addProduct(new Product('Floss', 5, 25.00));
        $this->supplier->addProduct(new Product('Floss', 1, 6.00));

        // Exact package matches
        $this->assertTrue($this->supplier->canFulfillQuantity('Floss', 5));
        $this->assertTrue($this->supplier->canFulfillQuantity('Floss', 1));

        // Combination matches
        $this->assertTrue($this->supplier->canFulfillQuantity('Floss', 6));
        $this->assertTrue($this->supplier->canFulfillQuantity('Floss', 7));

        // Not fulfillable cases
        $this->assertFalse($this->supplier->canFulfillQuantity('Floss', 0));
        $this->assertFalse($this->supplier->canFulfillQuantity('Floss', -1));
        $this->assertFalse($this->supplier->canFulfillQuantity('Nonexistent', 1));
    }
}