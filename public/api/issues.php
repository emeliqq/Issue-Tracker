<?php

require_once '../../app/models/IssueRepository.php';

header('Content-Type: application/json');

$repo = new IssueRepository();

/* GET -------------------------------------------------------------------- */

if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    echo json_encode(
        $repo->getAll(),
        JSON_PRETTY_PRINT
    );

    exit;
}

/* POST ------------------------------------------------------------------- */

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $data = json_decode(
        file_get_contents('php://input'),
        true
    );

    if (!$data) {

        http_response_code(400);

        echo json_encode([
            'error' => 'Invalid JSON'
        ]);

        exit;
    }

    $newIssue = [

        'id' => $repo->getNextId(),

        'summary' => $data['summary'] ?? '',

        'description' => $data['description'] ?? '',

        'steps_to_reproduce' =>
            $data['steps_to_reproduce'] ?? '',

        'priority' => $data['priority'] ?? 'Low',

        'status' => 'Open',

        'created_at' => date('Y-m-d H:i:s'),

        'updated_at' => null
    ];

    $repo->add($newIssue);

    http_response_code(201);

    echo json_encode([
        'message' => 'Issue created',
        'id' => $newIssue['id']
    ]);

    exit;
}

/* METHOD NOT ALLOWED ----------------------------------------------------- */

http_response_code(405);

echo json_encode([
    'error' => 'Method not allowed'
]);