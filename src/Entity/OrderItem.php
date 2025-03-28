<?php
namespace PriceComparison\Entity;

use InvalidArgumentException;
class OrderItem
{
    private string $productName;
    private int $quantity;

    public function __construct(string $productName, int $quantity)
    {
        $this->validateInput($productName, $quantity);

        $this->productName = $productName;
        $this->quantity = $quantity;
    }

    private function validateInput(string $productName, int $quantity): void
    {
        if (trim($productName) === '') {
            throw new InvalidArgumentException('Product name cannot be empty');
        }

        if ($quantity <= 0) {
            throw new InvalidArgumentException('Quantity must be positive');
        }
    }

    public function getProductName(): string
    {
        return $this->productName;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }
}