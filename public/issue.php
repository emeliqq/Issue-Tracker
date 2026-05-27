<?php

require_once '../app/models/IssueRepository.php';

$repo = new IssueRepository();

$id = $_GET['id'] ?? '';

$issue = $repo->findById($id);

if (!$issue) {
    die('Issue not found.');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Issue Details</title>

    <style>

        body {
            font-family: Arial, sans-serif;
            background: #f4f5f7;
            margin: 0;
        }

        header {
            background: #1d2125;
            color: white;
            padding: 20px;
        }

        .container {
            width: 90%;
            max-width: 900px;
            margin: 30px auto;
        }

        .issue-box {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }

        .section {
            margin-bottom: 25px;
        }

        .label {
            font-weight: bold;
            margin-bottom: 8px;
        }

        .back-btn {
            display: inline-block;
            margin-top: 20px;
            text-decoration: none;
            background: #0052cc;
            color: white;
            padding: 10px 16px;
            border-radius: 6px;
        }

    </style>

</head>
<body>

<header>
    <h1>Issue Details</h1>
</header>

<div class="container">

    <div class="issue-box">

        <div class="section">

            <div class="label">
                Summary
            </div>

            <?= htmlspecialchars($issue['summary'] ?? '') ?>

        </div>

        <div class="section">

            <div class="label">
                Description
            </div>

            <?= nl2br(htmlspecialchars($issue['description'] ?? '')) ?>

        </div>

        <div class="section">

            <div class="label">
                Steps to reproduce
            </div>

            <?= nl2br(htmlspecialchars($issue['steps_to_reproduce'] ?? '')) ?>

        </div>

        <div class="section">

            <div class="label">
                Status
            </div>

            <?= htmlspecialchars($issue['status'] ?? '') ?>

        </div>

        <div class="section">

            <div class="label">
                Priority
            </div>

            <?= htmlspecialchars($issue['priority'] ?? '') ?>

        </div>

        <div class="section">

            <div class="label">
                Created at
            </div>

            <?= htmlspecialchars($issue['created_at'] ?? '') ?>

        </div>

        <div class="section">

            <div class="label">
                Last updated
            </div>

            <?= htmlspecialchars($issue['updated_at'] ?? 'Never updated') ?>

        </div>

        <a
            href="issues.php"
            class="back-btn"
        >
            ← Back to Issues
        </a>

    </div>

</div>

</body>
</html>