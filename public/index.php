<?php
require __DIR__.'/../vendor/autoload.php';

use PriceComparison\Factory\InMemorySupplierFactory;
use PriceComparison\Service\BestOfferFinder;
use PriceComparison\Service\PriceCalculator;
use PriceComparison\Entity\OrderItem;

// Create order items
$orderItems = [
    new OrderItem('Dental Floss', 5),
    new OrderItem('Ibuprofen', 12)
];

// Get the best offer
$factory = new InMemorySupplierFactory();
$finder = new BestOfferFinder(
    $factory->createSupplierRepository(),
    new PriceCalculator()
);
$result = $finder->findBestOffer($orderItems);

// Output results
echo "Best Supplier: {$result['best_supplier_id']}\n";
echo "Best Price: {$result['best_price']} EUR\n";
echo "All Prices:\n";
print_r($result['all_prices']);