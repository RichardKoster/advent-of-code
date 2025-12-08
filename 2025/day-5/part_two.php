<?php

declare(strict_types=1);

$input = file_get_contents(__DIR__.'/input.txt');

[$freshRanges, $productIds] = array_map(
    'array_filter',
    array_map(
        fn(string $part) => preg_split('/\r\n|\n|\r/', $part),
        preg_split('/\n\W+/', $input)
    )
);

// Map string ranges like 1-12 to array ['start' => 1, 'end' => 12]
$ranges = [];
foreach ($freshRanges as $range) {
    if (preg_match('/(\d+)-(\d+)/', $range, $matches)) {
        // Store as integers (or GMP objects if needed)
        $ranges[] = ['start' => (int) $matches[1], 'end' => (int) $matches[2]];
    }
}

// Sort the ranges on the minimal value (start value)
usort($ranges, fn ($a, $b) => $a['start'] <=> $b['start']);

$mergedRanges = [];
// Set the initual cursor
$cursorStart = $ranges[0]['start'];
$cursorEnd = $ranges[0]['end'];

for ($i = 1; $i < count($ranges); $i++) {
    // Set a new cursor
    $nextCursorStart = $ranges[$i]['start'];
    $nextCursorEnd = $ranges[$i]['end'];

    // If the start of the new cursor is smaller than the end of the current cursor it falls between the current cursor.
    // Because of the sorting, the new start is always greater than the previous start but the new start may not be greater than the current end
    // If that is the case, the current end can be expanded to the new end
    if ($nextCursorStart < $cursorEnd + 1) {
        $cursorEnd = max($cursorEnd, $nextCursorEnd);
    } else {
        // This means that the new cursor start position is greater than the previous end position and therefore the current cursor may be saved
        $mergedRanges[] = ['start' => $cursorStart, 'end' => $cursorEnd];

        // Set the next cursor as the current cursor to restart the progress
        $cursorStart = $nextCursorStart;
        $cursorEnd = $nextCursorEnd;
    }
}

// Add the last cursor
$mergedRanges[] = ['start' => $cursorStart, 'end' => $cursorEnd];

// To count ranges the formula is max - min + 1
$count = 0;
foreach ($mergedRanges as $range) {
    $count += $range['end'] - $range['start'] + 1;
}