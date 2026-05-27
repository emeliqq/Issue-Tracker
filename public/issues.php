<?php

require_once '../app/models/IssueRepository.php';

$repo = new IssueRepository();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $newIssue = [
        'id' => $repo->getNextId(),
        'summary' => $_POST['summary'] ?? '',
        'description' => $_POST['description'] ?? '',
        'steps_to_reproduce' => $_POST['steps_to_reproduce'] ?? '',
        'priority' => $_POST['priority'] ?? 'Low',
        'status' => 'Open',
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => null
    ];

    $repo->add($newIssue);

    header('Location: issues.php');
    exit;
}

$issues = $repo->getAll();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Issues</title>

    <style>

        body {
            font-family: Arial, sans-serif;
            background: #f4f5f7;
            margin: 0;
            padding: 0;
        }

        header {
            background: #1d2125;
            color: white;
            padding: 20px;
        }

        .container {
            width: 90%;
            max-width: 1100px;
            margin: 30px auto;
        }

        .top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        button {
            cursor: pointer;
        }

        .create-btn {
            background: #0052cc;
            color: white;
            border: none;
            padding: 10px 18px;
            border-radius: 6px;
            font-size: 14px;
        }

        .issue-card {
            background: white;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 15px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.08);
        }

        .issue-meta {
            color: #666;
            font-size: 14px;
            margin-top: 10px;
            line-height: 1.7;
        }

        .priority {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 4px;
            font-size: 12px;
            margin-left: 10px;
            background: #dfe1e6;
        }

        .view-link {
            display: inline-block;
            margin-top: 15px;
            text-decoration: none;
            color: #0052cc;
            font-weight: bold;
        }

        /* MODAL */

        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
        }

        .modal-content {
            background: white;
            width: 90%;
            max-width: 600px;
            margin: 60px auto;
            padding: 30px;
            border-radius: 10px;
        }

        .modal-content h2 {
            margin-top: 0;
        }

        .form-group {
            margin-bottom: 20px;
        }

        input,
        textarea,
        select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 14px;
            box-sizing: border-box;
        }

        textarea {
            resize: vertical;
            min-height: 100px;
        }

        .modal-actions {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
        }

        .cancel-btn {
            background: #dfe1e6;
            border: none;
            padding: 10px 16px;
            border-radius: 6px;
        }

        .submit-btn {
            background: #0052cc;
            color: white;
            border: none;
            padding: 10px 16px;
            border-radius: 6px;
        }

    </style>
</head>
<body>

<header>
    <h1>Issue Tracker</h1>
</header>

<div class="container">

    <div class="top-bar">

        <h2>All Issues</h2>

        <button
            class="create-btn"
            onclick="openModal()"
        >
            Create Issue
        </button>

    </div>

    <?php if (empty($issues)): ?>

        <p>No issues found.</p>

    <?php else: ?>

        <?php foreach ($issues as $issue): ?>

            <div class="issue-card">

                <h3>
                    <?= htmlspecialchars($issue['summary'] ?? '') ?>
                </h3>

                <div class="issue-meta">

                    Status:
                    <strong>
                        <?= htmlspecialchars($issue['status'] ?? '') ?>
                    </strong>

                    <span class="priority">
                        <?= htmlspecialchars($issue['priority'] ?? '') ?>
                    </span>

                    <br><br>

                    <?php if (!empty($issue['updated_at'])): ?>

                        Last updated:
                        <?= htmlspecialchars($issue['updated_at']) ?>

                    <?php else: ?>

                        Created at:
                        <?= htmlspecialchars($issue['created_at'] ?? '') ?>

                    <?php endif; ?>

                </div>

                <a
                    href="issue.php?id=<?= $issue['id'] ?? '' ?>"
                    class="view-link"
                >
                    View Details
                </a>

            </div>

        <?php endforeach; ?>

    <?php endif; ?>

</div>

<!-- MODAL -->

<div id="issueModal" class="modal">

    <div class="modal-content">

        <h2>Create Issue</h2>

        <form method="POST">

            <div class="form-group">

                <input
                    type="text"
                    name="summary"
                    placeholder="Issue summary"
                    required
                >

            </div>

            <div class="form-group">

                <textarea
                    name="description"
                    placeholder="Issue description"
                ></textarea>

            </div>

            <div class="form-group">

                <textarea
                    name="steps_to_reproduce"
                    placeholder="Steps to reproduce"
                ></textarea>

            </div>

            <div class="form-group">

                <select name="priority">
                    <option value="Low">Low</option>
                    <option value="Medium">Medium</option>
                    <option value="High">High</option>
                </select>

            </div>

            <div class="modal-actions">

                <button
                    type="button"
                    class="cancel-btn"
                    onclick="closeModal()"
                >
                    Cancel
                </button>

                <button
                    type="submit"
                    class="submit-btn"
                >
                    Create
                </button>

            </div>

        </form>

    </div>

</div>

<script>

    const modal = document.getElementById('issueModal');

    function openModal() {
        modal.style.display = 'block';
    }

    function closeModal() {
        modal.style.display = 'none';
    }

    window.onclick = function(event) {

        if (event.target === modal) {
            closeModal();
        }

    }

</script>

</body>
</html>