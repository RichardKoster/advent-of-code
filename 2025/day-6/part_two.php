<?php

declare(strict_types=1);

$input = file_get_contents(__DIR__.'/input.txt');

$lines = preg_split("/\r\n|\n|\r/", $input);

$operators = $lines[4];
$maxStringLength = max(...array_map('strlen', $lines));
$operators = str_pad($operators, $maxStringLength);
$calculations = [];
for ($i = 0; $i < strlen($operators); $i++) {
    $operator = $operators[$i];
    $columnValue = (int) implode('', array_filter([
        $lines[0][$i],
        $lines[1][$i],
        $lines[2][$i],
        $lines[3][$i],
    ]));
    if (0 === $columnValue) {
        continue;
    }
    if (in_array($operator, ['+', '*'])) {
        $calculations[] = [
            'operator' => $operator,
            'values' => [$columnValue],
        ];
        continue;
    }
    array_unshift($calculations[count($calculations) - 1]['values'], $columnValue);
}

$count = 0;
foreach ($calculations as $set) {
    $count += match($set['operator']) {
        default => array_sum($set['values']),
        '*' => array_product($set['values']),
    };
}

print "Total is $count\n";