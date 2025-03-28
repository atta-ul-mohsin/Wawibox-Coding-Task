<?php
namespace PriceComparison\Service;

use PriceComparison\Entity\Supplier;

class PriceCalculator
{
    /**
     * @param Supplier $supplier
     * @param array $orderItems
     * @return float
     */
    public function calculateTotalCost(Supplier $supplier, array $orderItems): float
    {
        $total = 0.0;

        foreach ($orderItems as $item) {
            $prices = $supplier->getPricingForProduct($item->getProductName());
            $remaining = $item->getQuantity();
            $productTotal = 0.0;

            foreach ($prices as $packSize => $price) {
                if ($remaining <= 0) break;

                $numPacks = (int)($remaining / $packSize);
                if ($numPacks > 0) {
                    $productTotal += $numPacks * $price;
                    $remaining -= $numPacks * $packSize;
                }
            }

            if ($remaining > 0) {
                $productTotal += $remaining * $prices[1]; // Use single-unit price
            }

            $total += $productTotal;
        }

        return $total;
    }
}