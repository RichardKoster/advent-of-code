<?php

declare(strict_types=1);

$input = file_get_contents(__DIR__.'/input.txt');

function findAdjacentCoordinates(int $row, int $col, array $grid)
{
    $coordinates = [
        $grid[$row-1][$col-1] ?? null,
        $grid[$row-1][$col] ?? null,
        $grid[$row-1][$col+1] ?? null,
        $grid[$row][$col-1] ?? null,
        $grid[$row][$col+1] ?? null,
        $grid[$row+1][$col-1] ?? null,
        $grid[$row+1][$col] ?? null,
        $grid[$row+1][$col+1] ?? null,
    ];

    return array_filter($coordinates);
}

$lines = preg_split("/\r\n|\n|\r/", $input);
$grid = array_map('str_split', $lines);

function findAccessiblePapers(&$grid) {

    [$rows, $cols] = [\count($grid)-1, \count($grid[1])-1];

    $coordinatesMap = [];
    foreach (range(0, $rows) as $row) {
        foreach (range(0, $cols) as $col) {
            if ($grid[$row][$col] !== '@') {
                continue;
            }
            $coordinatesMap["$row-$col"] = findAdjacentCoordinates($row, $col, $grid);
        }
    }

    $amount = \count(
        array_filter($coordinatesMap, function(array $paperOrNot, string $key) use (&$grid) {
            $accessible = (array_count_values($paperOrNot)['@'] ?? 0) < 4;

            if ($accessible) {
                [$row, $col] = explode('-', $key);
                $grid[$row][$col] = '.';
            }

            return $accessible;
        }, ARRAY_FILTER_USE_BOTH)
    );


    print "$amount of accessible papers\n\n";

    return $amount;
}

$accessible = findAccessiblePapers($grid);

$newLines = array_map('implode', $grid);
$output = implode("\n", $newLines);

print $output.PHP_EOL.PHP_EOL;

print "$accessible possible to access";