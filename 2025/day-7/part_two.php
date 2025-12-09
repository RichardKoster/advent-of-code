<?php

declare(strict_types=1);

$input = file_get_contents(__DIR__ . '/input.txt');
$lines = preg_split('/\r\n|\n|\r/', $input);

function findCharIndices($line, $char) {
    $lastPos = 0;
    $positions = [];
    while (($lastPos = strpos($line, $char, $lastPos))!== false) {
        $positions[] = $lastPos;
        $lastPos = $lastPos + strlen($char);
    }

    return $positions;
}

$startIndex = strpos($lines[0], 'S');
$lines[1][$startIndex] = '|';
$timeLines = 1;
$splits = 0;
for ($i = 2; $i < count($lines); $i++) {
    $line = $lines[$i];
    $previousLine = $lines[$i-1];
    $beams = findCharIndices($previousLine, '|');
    $splitters = findCharIndices($line, '^');

    foreach ($beams as $beam) {
        if (in_array($beam, $splitters)) {
            continue;
        }
        $line[$beam] = '|';
    }
    foreach ($splitters as $splitter) {
        if (!in_array($splitter, $beams)) {
            continue; // Splitter is a dud because it has no beam above
        }
        $line[$splitter-1] = '|';
        $line[$splitter+1] = '|';
        ++$splits;
    }
    if ([] !== $splitters) {
        $timeLines += substr_count($line, '|');
    }

    $lines[$i] = $line;
}


$grid = array_map('str_split', $lines);
$startRow = 0;
$startCol = array_search('S', $grid[$startRow]);

$GLOBALS['carry'] = [];
function countFromPosition(int $row, int $col, array $grid) {
    if ($row >= count($grid) ) {
        return 1;
    }
    if ($col < 0 || $col >= count($grid[0])) {
        return 0;
    }

    $key = sprintf('%d-%d', $row, $col);
    if (isset($GLOBALS['carry'][$key])) {
        return $GLOBALS['carry'][$key];
    }

    $cell = $grid[$row][$col];
    $totalPaths = 0;
    if (in_array($cell, ['|', '.', 'S'])) {
        $totalPaths = countFromPosition($row + 1, $col, $grid);
    } elseif ($cell === '^') {
        $totalPaths = countFromPosition($row + 1, $col - 1, $grid) + countFromPosition($row + 1, $col + 1, $grid);
    }

    $GLOBALS['carry'][$key] = $totalPaths;
    return $totalPaths;
}

$timeLines = countFromPosition($startRow, $startCol, $grid);

print "There are $splits splits\n";
print "There are $timeLines timelines\n";
print implode("\n", $lines) . "\n";