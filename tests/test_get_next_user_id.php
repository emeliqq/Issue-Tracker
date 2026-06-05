<?php

function generateNextUserId(string $lastId): string
{
    $number = (int) str_replace(
        'USR-',
        '',
        $lastId
    );

    return 'USR-' . str_pad(
        $number + 1,
        5,
        '0',
        STR_PAD_LEFT
    );
}

$result = generateNextUserId('USR-00001');

if ($result === 'USR-00002') {

    echo "PASS\n";

} else {

    echo "FAIL\n";
}