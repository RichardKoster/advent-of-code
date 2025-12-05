<?php

declare(strict_types=1);

$input = file_get_contents(__DIR__ . '/input.txt');

$banks = preg_split('/\r\n|\r|\n/', $input);

$output = 0;
$range = range(11, 0);
$count = 0;
foreach ($banks as $bank) {
    $count++;
    // Split number string into char array and map to int values
    $jValues = str_split($bank);
    $jValues = array_map('intval', $jValues);

    $holder = $jValues;
    $joltage = [];
    foreach ($range as $value) {
        $possibleValues = $value !== 0 ? array_slice($holder, 0, -$value) : $holder;

        $joltage[] = \count($possibleValues) > 1 ? max(...$possibleValues) : reset($possibleValues);
        $holder = array_slice($holder, array_search(end($joltage), $possibleValues, true) + 1);
    }

    $output += (int) implode('', $joltage);
}

print "The total output is $output\n";