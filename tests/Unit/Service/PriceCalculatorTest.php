<?php
namespace Tests\PriceComparison\Unit\Service;

use PHPUnit\Framework\TestCase;
use PriceComparison\Entity\OrderItem;
use PriceComparison\Entity\Product;
use PriceComparison\Entity\Supplier;
use PriceComparison\Service\PriceCalculator;

class PriceCalculatorTest extends TestCase
{
    /**
     * @return void
     */
    public function testCalculateTotalCost()
    {
        $supplier = new Supplier('A');
        $supplier->addProduct(new Product('Floss', 10, 90.00));
        $supplier->addProduct(new Product('Floss', 1, 10.00));

        $calculator = new PriceCalculator();
        $total = $calculator->calculateTotalCost($supplier, [
            new OrderItem('Floss', 12)
        ]);

        $this->assertEquals(110.00, $total);
    }

    /**
     * @return void
     */
    public function testUsesCheapestCombination()
    {
        $supplier = new Supplier('B');
        $supplier->addProduct(new Product('Floss', 20, 160.00)); // 8/unit
        $supplier->addProduct(new Product('Floss', 10, 90.00));  // 9/unit
        $supplier->addProduct(new Product('Floss', 1, 10.00));   // 10/unit

        $calculator = new PriceCalculator();
        $total = $calculator->calculateTotalCost($supplier, [
            new OrderItem('Floss', 25)
        ]);

        $this->assertEquals(210.00, $total);
    }
}