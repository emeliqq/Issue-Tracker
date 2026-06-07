<?php

require_once '../../app/models/IssueRepository.php';

header('Content-Type: application/json');

$id = $_GET['id'] ?? '';

$repo = new IssueRepository();

$issue = $repo->findById($id);

if (!$issue) {

    http_response_code(404);

    echo json_encode([
        'error' => 'Issue not found'
    ]);

    exit;
}

echo json_encode(
    $issue,
    JSON_PRETTY_PRINT
);