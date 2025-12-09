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
        $lines[$i][$beam] = '|';
    }
    foreach ($splitters as $splitter) {
        if (!in_array($splitter, $beams)) {
            continue; // Splitter is a dud because it has no beam above
        }
        $lines[$i][$splitter-1] = '|';
        $lines[$i][$splitter+1] = '|';
        ++$splits;
    }
}

print "There are $splits splits\n";