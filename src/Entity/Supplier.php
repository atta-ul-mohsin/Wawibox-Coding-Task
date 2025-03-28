<?php
namespace PriceComparison\Entity;

class Supplier
{
    private string $id;
    /** @var Product[] */
    private array $products;

    public function __construct(string $id)
    {
        $this->id = $id;
        $this->products = [];
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param Product $product
     * @return void
     */
    public function addProduct(Product $product): void
    {
        $this->products[] = $product;
    }

    /**
     * @param string $productName
     * @return array<int, float> Package size => price
     */

    public function getPricingForProduct(string $productName): array
    {
        $prices = [];
        foreach ($this->products as $product) {
            if ($product->getName() === $productName) {
                $prices[$product->getPackageSize()] = $product->getPrice();
            }
        }
        krsort($prices); // Sort by package size descending
        return $prices;
    }

    /**
     * @param string $productName
     * @return bool
     */
    public function canSupplyProduct(string $productName): bool
    {
        foreach ($this->products as $product) {
            if ($product->getName() === $productName) {
                return true;
            }
        }
        return false;
    }

    /**
     * @param string $productName
     * @param int $quantity
     * @return bool
     */
    public function canFulfillQuantity(string $productName, int $quantity): bool
    {
        if ($quantity <= 0) {
            return false;
        }

        if (!$this->canSupplyProduct($productName)) {
            return false;
        }

        $prices = $this->getPricingForProduct($productName);
        $remaining = $quantity;

        foreach ($prices as $packSize => $price) {
            if ($remaining <= 0) break;

            $numPacks = (int)($remaining / $packSize);
            $remaining -= $numPacks * $packSize;
        }

        // Check if we can handle remaining units
        if ($remaining > 0) {
            return isset($prices[1]); // Has single-unit option
        }

        return true;
    }
}