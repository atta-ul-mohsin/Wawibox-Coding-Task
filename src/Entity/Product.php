<?php
namespace PriceComparison\Entity;

use InvalidArgumentException;
class Product {
    private string $name;
    private int $packageSize;
    private float $price;

    public function __construct(string $name, int $packageSize, float $price) {
        if ($packageSize <= 0) {
            throw new InvalidArgumentException("Package size must be positive");
        }
        if ($price <= 0) {
            throw new InvalidArgumentException("Price must be positive");
        }

        $this->name = $name;
        $this->packageSize = $packageSize;
        $this->price = $price;
    }

    /**
     * @return string
     */
    public function getName(): string {
        return $this->name;
    }

    /**
     * @return int
     */
    public function getPackageSize(): int {
        return $this->packageSize;
    }

    /**
     * @return float
     */
    public function getPrice(): float {
        return $this->price;
    }
}