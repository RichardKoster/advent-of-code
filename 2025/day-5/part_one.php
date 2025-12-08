<?php

declare(strict_types=1);

$input = file_get_contents(__DIR__.'/input.txt');

[$freshRanges, $productIds] = array_map(
    'array_filter',
    array_map(
        fn(string $part) => preg_split('/\r\n|\n|\r/', $part),
        preg_split('/\n\W+/', $input)
    )
);

$freshIds = array_map(
    function (string $range) {
        [$min, $max] = explode('-', $range);

        return [(int)$min, (int)$max];
    },
    $freshRanges
);

$productIds = array_map('intval', $productIds);
$freshProducts = array_filter(
    $productIds,
    function (int $productId) use ($freshIds) {
        foreach ($freshIds as $range) {
            if ($productId >= $range[0] && $productId <= $range[1]) {
                return true;
            }
        }

        return false;
    }
);
$freshProductsCount = count($freshProducts);

print "There are $freshProductsCount products\n";