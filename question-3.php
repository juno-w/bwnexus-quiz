<?php

require 'vendor/autoload.php';

use Predis\Client;

// Requires: composer require predis/predis


function placeOrder($userId, $productId)
{
    // Connect to Redis (adjust parameters as needed for cluster/scalability)
    $redis = new Client([
        'scheme' => 'tcp',
        'host'   => '127.0.0.1',
        'port'   => 6379,
        // For Redis cluster: add 'cluster' => 'redis'
    ]);

    // Unique key per user-product pair
    $key = "order_lock:user:{$userId}:product:{$productId}";

    // Try to set the lock with a 5-second TTL, only if it doesn't exist
    $result = $redis->set($key, 1, 'NX', 'EX', 5);

    if ($result) {
        // Order placed successfully
        // ... (Place order logic here, e.g., write to DB)
        return "success\n";
    } else {
        // Duplicate order attempt within 5 seconds
        return "duplicate\n";
    }
}

// Example usage:
for ($i = 0; $i < 10; $i++) {
    sleep(1);
    echo placeOrder(123, 456);
}
