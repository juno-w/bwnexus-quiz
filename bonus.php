<?php

function simulateApiCall($name, $delay)
{
    echo "Starting API call: $name\n";
    $start = microtime(true);
    // Simulate async delay
    while ((microtime(true) - $start) < $delay) {
        yield;
    }
    echo "Finished API call: $name\n";
}

function simulateDbInsert($table, $delay)
{
    echo "Starting DB insert into $table\n";
    $start = microtime(true);
    // Simulate async delay
    while ((microtime(true) - $start) < $delay) {
        yield;
    }
    echo "Finished DB insert into $table\n";
}

// Simple event loop for generators
function runTasks(array $tasks)
{
    $active = $tasks;
    while (!empty($active)) {
        foreach ($active as $i => $task) {
            if ($task->valid()) {
                $task->current();
                $task->next();
            } else {
                unset($active[$i]);
            }
        }
    }
}

// Schedule two tasks
$startTime = microtime(true);

$tasks = [
    simulateApiCall('getUser', 2),      // Simulate 2 seconds API call
    simulateDbInsert('users', 3),       // Simulate 3 seconds DB insert
];

runTasks($tasks);

$totalTime = microtime(true) - $startTime;
echo "Total execution time: " . round($totalTime, 2) . " seconds\n";
