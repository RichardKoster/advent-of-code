<?php

declare(strict_types=1);

$input = file_get_contents(__DIR__ . '/input.txt');

$ranges = explode(",", $input);

$sum = 0;
foreach ($ranges as $range) {
    [$a, $b] = explode("-", $range);

    foreach (range($a, $b) as $c) {
        $cLength = strlen((string) $c);
        if ($cLength % 2 !== 0) {
            continue;
        }
        $values = str_split((string) $c, $cLength/2);
        if ($values[0] === $values[1]) {
            $sum += (int) $c;
        }
    }
}

print "Sum of invalid ids is $sum\n";