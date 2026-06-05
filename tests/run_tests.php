<?php

$passed = 0;
$failed = 0;

function assertEquals(
    $expected,
    $actual,
    string $testName
): void {

    global $passed, $failed;

    if ($expected === $actual) {

        echo "[PASS] $testName\n";

        $passed++;

    } else {

        echo "[FAIL] $testName\n";

        echo "Expected: $expected\n";

        echo "Actual: $actual\n\n";

        $failed++;
    }
}

/* TEST 1 ------------------------------------------------------------------ */

function generateNextUserId(
    string $lastId
): string {

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

assertEquals(
    'USR-00002',
    generateNextUserId('USR-00001'),
    'Generate next user ID'
);

/* TEST 2 ------------------------------------------------------------------ */

function generateNextIssueId(
    string $lastId
): string {

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

assertEquals(
    'ISS-00016',
    generateNextIssueId('ISS-00015'),
    'Generate next issue ID'
);

/* SUMMARY ----------------------------------------------------------------- */

echo "\n";

echo "Tests run: " . ($passed + $failed) . "\n";

echo "Passed: $passed\n";

echo "Failed: $failed\n";