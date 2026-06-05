<?php

function generateNextIssueId(string $lastId): string
{
    $number = (int) str_replace(
        'ISS-',
        '',
        $lastId
    );

    return 'ISS-' . str_pad(
        $number + 1,
        5,
        '0',
        STR_PAD_LEFT
    );
}

$result = generateNextIssueId('ISS-00015');

if ($result === 'ISS-00016') {

    echo "PASS\n";

} else {

    echo "FAIL\n";
}