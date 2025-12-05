<?php

declare(strict_types=1);

$input = file_get_contents(__DIR__ . '/input.txt');
$rotations = preg_split('/(\r\n|\n|\r)/', $input);

$dial = 50;
$zeroes = 0;
$fullRotations = 0;
foreach ($rotations as $rotation) {
    $direction = substr($rotation, 0, 1);
    $count = (int) substr($rotation, 1);

    if ($direction === 'R') {
        $futureDialPosition = ($dial + $count) % 100;

        $crossesZero = floor(($dial + $count) / 100);
        $zeroes += $crossesZero;

        $dial = $futureDialPosition;

        continue;
    }

    $futureDialPosition = (($dial - $count) % 100 + 100) % 100;
    if ($count > $dial) {
        $crossesZero = ceil(($count - $dial) / 100);
        $zeroes += $crossesZero;
    }
    $dial = $futureDialPosition;
}

print "The dial has passed `0` for $zeroes times.\n";
print "The dial ended on $dial.\n";
