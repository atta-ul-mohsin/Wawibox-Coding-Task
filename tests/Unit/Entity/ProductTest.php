<?php
namespace Tests\PriceComparison\Unit\Entity;

use PHPUnit\Framework\TestCase;
use PriceComparison\Entity\Product;

class ProductTest extends TestCase
{
    public function testProductCreation()
    {
        $product = new Product('Floss', 1, 9.99);
        $this->assertEquals('Floss', $product->getName());
        $this->assertEquals(1, $product->getPackageSize());
        $this->assertEquals(9.99, $product->getPrice());
    }

    public function testInvalidPriceThrowsException()
    {
        $this->expectException(\InvalidArgumentException::class);
        new Product('Floss', 1, -5.00);
    }
}