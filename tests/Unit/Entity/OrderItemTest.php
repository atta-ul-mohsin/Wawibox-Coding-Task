<?php
namespace Tests\PriceComparison\Unit\Entity;

use PHPUnit\Framework\TestCase;
use PriceComparison\Entity\OrderItem;

class OrderItemTest extends TestCase
{
    public function testValidOrderItem()
    {
        $item = new OrderItem('Floss', 5);
        $this->assertEquals('Floss', $item->getProductName());
        $this->assertEquals(5, $item->getQuantity());
    }

    public function testInvalidQuantityThrowsException()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Quantity must be positive');
        new OrderItem('Floss', 0);
    }

    public function testNegativeQuantityThrowsException()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Quantity must be positive');
        new OrderItem('Floss', -1);
    }

    public function testEmptyProductNameThrowsException()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Product name cannot be empty');
        new OrderItem('', 1);
    }

    public function testWhitespaceProductNameThrowsException()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Product name cannot be empty');
        new OrderItem('   ', 1);
    }
}