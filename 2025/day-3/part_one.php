<?php

declare(strict_types=1);

$input = file_get_contents(__DIR__ . '/input.txt');

$banks = preg_split('/\r\n|\r|\n/', $input);

$output = 0;
foreach ($banks as $bank) {
    // Split number string into char array and map to int values
    $jValues = str_split($bank);
    $jValues = array_map('intval', $jValues);

    // Tens value can never be the last value, so have a copy of jValues without the last value.
    // The largest value as tens will always result in the biggest number, regardless of whats after it
    $tensValues = $jValues;
    array_pop($tensValues);
    $tens = max(...$tensValues);

    // The singular value can only come after the tens value, therefore slice the array so only values that come
    // after the tens are available to choose from as a singular. Choose the max value to have the biggest joltage
    $singularJValues = $jValues;
    $singularJValues = array_slice(
        $jValues,
        array_search($tens, $jValues, true) + 1
    );
    $singular = \count($singularJValues) > 1 ? max(...$singularJValues) : reset($singularJValues);

    // Add the calculated joltage to the output.
    $output += (int) ($tens.$singular);
}

print "The total output is $output\n";