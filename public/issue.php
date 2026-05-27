<?php

require_once '../app/models/IssueRepository.php';

$repo = new IssueRepository();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $id = $_POST['id'] ?? '';
    $field = $_POST['field'] ?? '';
    $value = $_POST['value'] ?? '';

    $allowedFields = [
        'summary',
        'description',
        'steps_to_reproduce',
        'status',
        'priority'
    ];

    if (
        $id &&
        in_array($field, $allowedFields)
    ) {

        $repo->updateField($id, $field, $value);

    }

    header("Location: issue.php?id=$id");
    exit;
}

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
            margin-bottom: 35px;
        }

        .label {
            font-weight: bold;
            margin-bottom: 10px;
        }

        .value {
            margin-bottom: 10px;
            line-height: 1.6;
        }

        .edit-btn,
        .save-btn,
        .cancel-btn,
        .back-btn {
            background: #0052cc;
            color: white;
            border: none;
            padding: 10px 16px;
            border-radius: 6px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }

        .cancel-btn {
            background: #666;
        }

        input,
        textarea,
        select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 6px;
            box-sizing: border-box;
            margin-bottom: 10px;
            font-family: Arial, sans-serif;
        }

        textarea {
            min-height: 120px;
            resize: vertical;
        }

        .inline-form {
            max-width: 600px;
        }

        .meta {
            color: #666;
            font-size: 14px;
        }

    </style>

</head>
<body>

<header>
    <h1>Issue Details</h1>
</header>

<div class="container">

    <div class="issue-box">

        <!-- SUMMARY -->

        <div class="section">

            <div class="label">
                Summary
            </div>

            <div id="summary-view">

                <div class="value">
                    <?= htmlspecialchars($issue['summary'] ?? '') ?>
                </div>

                <button
                    class="edit-btn"
                    onclick="toggleSection('summary', true)"
                >
                    Edit
                </button>

            </div>

            <div
                id="summary-edit"
                style="display:none;"
            >

                <form
                    method="POST"
                    class="inline-form"
                >

                    <input
                        type="hidden"
                        name="id"
                        value="<?= htmlspecialchars($issue['id']) ?>"
                    >

                    <input
                        type="hidden"
                        name="field"
                        value="summary"
                    >

                    <input
                        type="text"
                        name="value"
                        value="<?= htmlspecialchars($issue['summary']) ?>"
                    >

                    <button
                        type="submit"
                        class="save-btn"
                    >
                        Save
                    </button>

                    <button
                        type="button"
                        class="cancel-btn"
                        onclick="toggleSection('summary', false)"
                    >
                        Cancel
                    </button>

                </form>

            </div>

        </div>

        <!-- DESCRIPTION -->

        <div class="section">

            <div class="label">
                Description
            </div>

            <div id="description-view">

                <div class="value">
                    <?= nl2br(htmlspecialchars($issue['description'] ?? '')) ?>
                </div>

                <button
                    class="edit-btn"
                    onclick="toggleSection('description', true)"
                >
                    Edit
                </button>

            </div>

            <div
                id="description-edit"
                style="display:none;"
            >

                <form
                    method="POST"
                    class="inline-form"
                >

                    <input
                        type="hidden"
                        name="id"
                        value="<?= htmlspecialchars($issue['id']) ?>"
                    >

                    <input
                        type="hidden"
                        name="field"
                        value="description"
                    >

                    <textarea
                        name="value"
                    ><?= htmlspecialchars($issue['description']) ?></textarea>

                    <button
                        type="submit"
                        class="save-btn"
                    >
                        Save
                    </button>

                    <button
                        type="button"
                        class="cancel-btn"
                        onclick="toggleSection('description', false)"
                    >
                        Cancel
                    </button>

                </form>

            </div>

        </div>

        <!-- STEPS -->

        <div class="section">

            <div class="label">
                Steps to reproduce
            </div>

            <div id="steps_to_reproduce-view">

                <div class="value">
                    <?= nl2br(htmlspecialchars($issue['steps_to_reproduce'] ?? '')) ?>
                </div>

                <button
                    class="edit-btn"
                    onclick="toggleSection('steps_to_reproduce', true)"
                >
                    Edit
                </button>

            </div>

            <div
                id="steps_to_reproduce-edit"
                style="display:none;"
            >

                <form
                    method="POST"
                    class="inline-form"
                >

                    <input
                        type="hidden"
                        name="id"
                        value="<?= htmlspecialchars($issue['id']) ?>"
                    >

                    <input
                        type="hidden"
                        name="field"
                        value="steps_to_reproduce"
                    >

                    <textarea
                        name="value"
                    ><?= htmlspecialchars($issue['steps_to_reproduce']) ?></textarea>

                    <button
                        type="submit"
                        class="save-btn"
                    >
                        Save
                    </button>

                    <button
                        type="button"
                        class="cancel-btn"
                        onclick="toggleSection('steps_to_reproduce', false)"
                    >
                        Cancel
                    </button>

                </form>

            </div>

        </div>

        <!-- STATUS -->

        <div class="section">

            <div class="label">
                Status
            </div>

            <form
                method="POST"
                class="inline-form"
            >

                <input
                    type="hidden"
                    name="id"
                    value="<?= htmlspecialchars($issue['id']) ?>"
                >

                <input
                    type="hidden"
                    name="field"
                    value="status"
                >

                <select name="value">

                    <option
                        value="Open"
                        <?= (($issue['status'] ?? '') === 'Open') ? 'selected' : '' ?>
                    >
                        Open
                    </option>

                    <option
                        value="In Progress"
                        <?= (($issue['status'] ?? '') === 'In Progress') ? 'selected' : '' ?>
                    >
                        In Progress
                    </option>

                    <option
                        value="Resolved"
                        <?= (($issue['status'] ?? '') === 'Resolved') ? 'selected' : '' ?>
                    >
                        Resolved
                    </option>

                </select>

                <button
                    type="submit"
                    class="save-btn"
                >
                    Save
                </button>

            </form>

        </div>

        <!-- PRIORITY -->

        <div class="section">

            <div class="label">
                Priority
            </div>

            <form
                method="POST"
                class="inline-form"
            >

                <input
                    type="hidden"
                    name="id"
                    value="<?= htmlspecialchars($issue['id']) ?>"
                >

                <input
                    type="hidden"
                    name="field"
                    value="priority"
                >

                <select name="value">

                    <option
                        value="Low"
                        <?= (($issue['priority'] ?? '') === 'Low') ? 'selected' : '' ?>
                    >
                        Low
                    </option>

                    <option
                        value="Medium"
                        <?= (($issue['priority'] ?? '') === 'Medium') ? 'selected' : '' ?>
                    >
                        Medium
                    </option>

                    <option
                        value="High"
                        <?= (($issue['priority'] ?? '') === 'High') ? 'selected' : '' ?>
                    >
                        High
                    </option>

                </select>

                <button
                    type="submit"
                    class="save-btn"
                >
                    Save
                </button>

            </form>

        </div>

        <!-- CREATED -->

        <div class="section">

            <div class="label">
                Created at
            </div>

            <div class="meta">
                <?= htmlspecialchars($issue['created_at'] ?? '') ?>
            </div>

        </div>

        <!-- UPDATED -->

        <div class="section">

            <div class="label">
                Last updated
            </div>

            <div class="meta">
                <?= htmlspecialchars($issue['updated_at'] ?? 'Never updated') ?>
            </div>

        </div>

        <a
            href="issues.php"
            class="back-btn"
        >
            ← Back to Issues
        </a>

    </div>

</div>

<script>

    function toggleSection(section, editMode) {

        const view = document.getElementById(section + '-view');
        const edit = document.getElementById(section + '-edit');

        if (editMode) {

            view.style.display = 'none';
            edit.style.display = 'block';

        } else {

            view.style.display = 'block';
            edit.style.display = 'none';

        }

    }

</script>

</body>
</html>