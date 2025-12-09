<?php

declare(strict_types=1);

$input = file_get_contents(__DIR__ . '/input.txt');

$lines = preg_split("/\r\n|\n|\r/", $input);

$grid = array_map(fn (string $line) => preg_split('/\s/', $line), $lines);

$map = [];
foreach ($grid[0] as $index => $column) {
    $map[$index] = [
        'operator' => $grid[4][$index],
        'values' => [
            (int) $column,
            (int) $grid[1][$index],
            (int) $grid[2][$index],
            (int) $grid[3][$index],
        ],
    ];
}

$count = 0;
foreach ($map as $set) {
    $count += match($set['operator']) {
        default => array_sum($set['values']),
        '*' => array_product($set['values']),
    };
}

print "Total is $count\n";