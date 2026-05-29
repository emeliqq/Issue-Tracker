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

    <link
        rel="stylesheet"
        href="assets/css/issue.css"
        
    >
    <link rel="stylesheet" href="/Issue-Tracker/public/assets/css/global.css">

</head>
<body>

<header class="topbar">

    <a
        href="issues.php"
        class="back-link"
    >
        ← Back to Issues
    </a>

</header>


<div class="page-container">

    <!-- LEFT -->

    <div class="content-column">

        <div class="issue-id">
            <?= htmlspecialchars($issue['id'] ?? '') ?>
        </div>

        <!-- SUMMARY -->

        <div class="summary-section">

            <div id="summary-view">

                <div class="summary-row">

                    <h1 class="summary-title">
                        <?= htmlspecialchars($issue['summary'] ?? '') ?>
                    </h1>

                    <button
                        class="edit-btn"
                        onclick="toggleSection('summary', true)"
                    >
                        Edit
                    </button>

                </div>

            </div>

            <div
                id="summary-edit"
                style="display:none;"
            >

                <form method="POST">

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

                    <div class="button-group">

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

                    </div>

                </form>

            </div>

        </div>

        <!-- DESCRIPTION -->

        <div class="content-box">

            <div class="section-header">

                <h2>Description</h2>

                <button
                    class="edit-btn"
                    onclick="toggleSection('description', true)"
                >
                    Edit
                </button>

            </div>

            <div id="description-view">

                <div class="content-text">
                    <?= nl2br(
                        htmlspecialchars(
                            trim($issue['description'] ?? '')
                        )
                    ) ?>
                </div>

            </div>

            <div
                id="description-edit"
                style="display:none;"
            >

                <form method="POST">

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

                    <div class="button-group">

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

                    </div>

                </form>

            </div>

        </div>

        <!-- STEPS -->

        <div class="content-box">

            <div class="section-header">

                <h2>Steps to reproduce</h2>

                <button
                    class="edit-btn"
                    onclick="toggleSection('steps', true)"
                >
                    Edit
                </button>

            </div>

            <div id="steps-view">

                <div class="content-text">
                    <?= nl2br(
                        htmlspecialchars(
                            trim($issue['steps_to_reproduce'] ?? '')
                        )
                    ) ?>
                </div>

            </div>

            <div
                id="steps-edit"
                style="display:none;"
            >

                <form method="POST">

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

                    <div class="button-group">

                        <button
                            type="submit"
                            class="save-btn"
                        >
                            Save
                        </button>

                        <button
                            type="button"
                            class="cancel-btn"
                            onclick="toggleSection('steps', false)"
                        >
                            Cancel
                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>

    <!-- RIGHT -->

    <div class="sidebar-column">

        <!-- STATUS + SEVERITY -->

        <div class="sidebar-box">

            <!-- STATUS -->

            <div class="sidebar-form">

                <h3>Status</h3>

                <form method="POST">

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

                    <div class="sidebar-row">

                        <select
                            name="value"
                            onchange="this.form.submit()"
                        >

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

                    </div>

                </form>

            </div>

            <hr>

            <!-- PRIORITY -->

            <div class="sidebar-form">

                <h3>Severity</h3>

                <form method="POST">

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

                    <div class="sidebar-row">

                        <select
                            name="value"
                            onchange="this.form.submit()"
                        >

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

                    </div>

                </form>

            </div>

        </div>

        <!-- PEOPLE + DATES -->

        <div class="sidebar-box">

            <div class="meta-item">

                <strong>Reporter:</strong>
                Admin

            </div>

            <div class="meta-item">

                <strong>Updater:</strong>
                Admin

            </div>

            <hr>

            <div class="meta-item">

                <strong>Created:</strong>

                <br>

                <?= htmlspecialchars($issue['created_at'] ?? '') ?>

            </div>

            <div class="meta-item">

                <strong>Last update:</strong>

                <br>

                <?= htmlspecialchars($issue['updated_at'] ?? 'Never updated') ?>

            </div>

        </div>

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

<?php require '../app/views/partials/footer.php'; ?>