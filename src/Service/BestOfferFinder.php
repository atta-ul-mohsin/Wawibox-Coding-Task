<?php
namespace PriceComparison\Service;

use PriceComparison\Repository\SupplierRepositoryInterface;
use PriceComparison\Entity\Supplier;
use PriceComparison\Entity\OrderItem;
use RuntimeException;
use InvalidArgumentException;

class BestOfferFinder
{
    public function __construct(
        private SupplierRepositoryInterface $supplierRepository,
        private PriceCalculator $priceCalculator
    ) {}

    /**
     * Finds the best supplier for the given order items
     *
     * @param OrderItem[] $orderItems Array of order items to compare
     * @return array{
     *     best_supplier_id: string,
     *     best_price: float,
     *     all_prices: array<string, float>
     * }
     * @throws InvalidArgumentException When order items are empty
     * @throws RuntimeException When no supplier can fulfill the order
     */
    public function findBestOffer(array $orderItems): array
    {
        $this->validateOrderItems($orderItems);

        $supplierTotals = $this->calculateSupplierTotals($orderItems);
        asort($supplierTotals);

        return [
            'best_supplier_id' => key($supplierTotals),
            'best_price' => current($supplierTotals),
            'all_prices' => $supplierTotals
        ];
    }

    /**
     * Validates order items
     * @param array $orderItems
     * @throws InvalidArgumentException
     */
    private function validateOrderItems(array $orderItems): void
    {
        if (empty($orderItems)) {
            throw new InvalidArgumentException("Order items cannot be empty");
        }
    }

    /**
     * Calculates totals for all eligible suppliers
     * @param array $orderItems
     * @return array
     * @throws RuntimeException
     */
    private function calculateSupplierTotals(array $orderItems): array
    {
        $supplierTotals = [];
        $eligibleSuppliers = $this->findEligibleSuppliers($orderItems);

        foreach ($eligibleSuppliers as $supplier) {
            $supplierTotals[$supplier->getId()] = $this->priceCalculator->calculateTotalCost(
                $supplier,
                $orderItems
            );
        }

        if (empty($supplierTotals)) {
            throw new RuntimeException("No supplier can fulfill the entire order");
        }

        return $supplierTotals;
    }

    /**
     * Finds suppliers that can fulfill all order items
     * @param array $orderItems
     * @return array
     */
    private function findEligibleSuppliers(array $orderItems): array
    {
        return array_filter(
            $this->supplierRepository->findAll(),
            fn(Supplier $supplier) => $this->canSupplierFulfillOrder($supplier, $orderItems)
        );
    }

    /**
     * Checks if supplier can fulfill all items in the order
     * @param Supplier $supplier
     * @param array $orderItems
     * @return bool
     */
    private function canSupplierFulfillOrder(Supplier $supplier, array $orderItems): bool
    {
        foreach ($orderItems as $item) {
            if (!$supplier->canFulfillQuantity($item->getProductName(), $item->getQuantity())) {
                return false;
            }
        }
        return true;
    }
}