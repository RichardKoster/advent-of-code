<?php

declare(strict_types=1);

$input = file_get_contents(__DIR__ . '/input.txt');
$rotations = preg_split('/(\r\n|\n|\r)/', $input);

$dial = 50;
$zeroes = 0;
foreach ($rotations as $rotation) {
    $direction = substr($rotation, 0, 1);
    $count = (int) substr($rotation, 1);
    $direction = $direction === 'L' ? -1 : 1;

    $displacement = $count * $direction;
    $dial = ($dial + $displacement) % 100;
    if ($dial === 0) {
        ++$zeroes;
    }
}

print "The dial was at `0` for $zeroes times.\n";
