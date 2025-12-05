<?php

declare(strict_types=1);

$input = file_get_contents(__DIR__ . '/input.txt');

$ranges = explode(",", $input);

$ids = [];
foreach ($ranges as $range) {
    [$a, $b] = explode("-", $range);
    $ids = array_merge($ids, range($a, $b));
}
sort($ids);

$sum = 0;
foreach ($ids as $id) {
    $idLength = strlen((string) $id);
    for ($i = 1; $i < $idLength; $i++) {
        if ($idLength % $i !== 0){
            continue;
        }
        $parts = str_split((string) $id, $i);
        if (\count(array_unique($parts)) === 1) {
            $sum += (int) $id;
            continue 2;
        }
    }
}

print "Sum of invalid ids is $sum\n";