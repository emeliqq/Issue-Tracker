<?php

/* AUTHENTICATION ----------------------------------------------------------------------------------------------------------------------------------------------------------- */

session_start();

if (!isset($_SESSION['user'])) {

    header('Location: login.php');
    exit;
}

/* DEPENDENCIES ------------------------------------------------------------------------------------------------------------------------------------------------------------- */

require '../app/views/partials/header.php'; 

require_once '../app/models/IssueRepository.php';

$repo = new IssueRepository();

/* FILTERS ------------------------------------------------------------------------------------------------------------------------------------------------------------------ */

$search = trim($_GET['search'] ?? '');
$sort = $_GET['sort'] ?? 'newest';
$statusFilter = $_GET['status'] ?? '';
$severityFilter = $_GET['severity'] ?? '';
$assignmentFilter = $_GET['assignment'] ?? '';

/* CREATE ISSUE ------------------------------------------------------------------------------------------------------------------------------------------------------------- */

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $newIssue = [
    'id' => $repo->getNextId(),
    'summary' => $_POST['summary'] ?? '',
    'description' => $_POST['description'] ?? '',
    'steps_to_reproduce' => $_POST['steps_to_reproduce'] ?? '',
    'priority' => $_POST['priority'] ?? 'Low',
    'status' => 'Open',
    'reporter' => $_SESSION['user']['id'],
    'created_at' => date('Y-m-d H:i:s'),
    'updated_at' => null,
    'assignee' => null,
    'updater' => null,
];

    $repo->add($newIssue);

    header('Location: issues.php');
    exit;
}

/* LOAD ISSUES ------------------------------------------------------------------------------------------------------------------------------------------------------------- */

$issues = $repo->getAll();

$statusMap = [
    1 => 'Open',
    2 => 'In Progress',
    3 => 'Resolved'
];

$severityMap = [
    1 => 'Low',
    2 => 'Medium',
    3 => 'High'
];

switch ($sort) {

    case 'oldest':
        break;

    case 'updated':

        usort(
            $issues,
            function ($a, $b) {

                $aTime = strtotime(
                    $a['updated_at']
                    ?? '1970-01-01'
                );

                $bTime = strtotime(
                    $b['updated_at']
                    ?? '1970-01-01'
                );

                return $bTime <=> $aTime;
            }
        );

        break;

    case 'newest':
    default:

        $issues = array_reverse($issues);

        break;
}

/* FILTERING --------------------------------------------------------------------------------------------------------------------------------------------------------------- */

if (!empty($search)) {

    $issues = array_filter(
        $issues,
        function ($issue) use ($search) {

            $keyword = strtolower($search);

            return
                str_contains(
                    strtolower($issue['summary'] ?? ''),
                    $keyword
                )
                ||
                str_contains(
                    strtolower($issue['description'] ?? ''),
                    $keyword
                );
        }
    );
}

if (!empty($statusFilter)) {

    $issues = array_filter(
        $issues,
        function ($issue) use (
            $statusFilter,
            $statusMap
        ) {

            return (
                $statusMap[$issue['status_id']]
                ?? ''
            ) === $statusFilter;
        }
    );
}

if (!empty($severityFilter)) {

    $issues = array_filter(
        $issues,
        function ($issue) use (
            $severityFilter,
            $severityMap
        ) {

            return (
                $severityMap[$issue['severity_id']]
                ?? ''
            ) === $severityFilter;
        }
    );
}

if ($assignmentFilter === 'assigned') {

    $issues = array_filter(
        $issues,
        fn($issue) =>
            !empty($issue['assignee_id'])
    );
}

if ($assignmentFilter === 'unassigned') {

    $issues = array_filter(
        $issues,
        fn($issue) =>
            empty($issue['assignee_id'])
    );
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Issues</title>
    <link
        rel="stylesheet"
        href="assets/css/issues.css"
    >
    
</head>
<body>

<!-- PAGE CONTENT ------------------------------------------------------------------------------------------------------------------------------------------------------------->

<div class="container">

<!-- PAGE HEADER ------------------------------------------------------------------------------------------------------------------------------------------------------------->

    <div class="top-bar">

        <h2>All Issues</h2>

        <button
            class="create-btn"
            onclick="openModal()"
        >
            Create Issue
        </button>

    </div>

<!-- FILTER TOOLBAR ---------------------------------------------------------------------------------------------------------------------------------------------------------->

    <div class="toolbar">

        <form method="GET" class="filters-form">

<!-- KEYWORDS REARCH --------------------------------------------------------------------------------------------------------------------------------------------------------->

            <input
                type="text"
                name="search"
                placeholder="Search keywords..."
                value="<?= htmlspecialchars($search) ?>"
            >

<!-- BUTTON SEARCH ----------------------------------------------------------------------------------------------------------------------------------------------------------->

            <button type="submit">
                Search
            </button>

<!-- FILTER STATUS ----------------------------------------------------------------------------------------------------------------------------------------------------------->

            <select
                name="status"
                onchange="this.form.submit()"
            >
                <option value="">
                    All Statuses
                </option>

                <option
                    value="Open"
                    <?= $statusFilter === 'Open' ? 'selected' : '' ?>
                >
                    Open
                </option>

                <option
                    value="In Progress"
                    <?= $statusFilter === 'In Progress' ? 'selected' : '' ?>
                >
                    In Progress
                </option>

                <option
                    value="Resolved"
                    <?= $statusFilter === 'Resolved' ? 'selected' : '' ?>
                >
                    Resolved
                </option>

            </select>

<!-- FILTER SEVERITY --------------------------------------------------------------------------------------------------------------------------------------------------------->

            <select
                name="severity"
                onchange="this.form.submit()"
            >

                <option value="">
                    All Severities
                </option>

                <option
                    value="Low"
                    <?= $severityFilter === 'Low' ? 'selected' : '' ?>
                >
                    Low
                </option>

                <option
                    value="Medium"
                    <?= $severityFilter === 'Medium' ? 'selected' : '' ?>
                >
                    Medium
                </option>

                <option
                    value="High"
                    <?= $severityFilter === 'High' ? 'selected' : '' ?>
                >
                    High
                </option>

            </select>

<!-- FILTER ASSIGNMENT ------------------------------------------------------------------------------------------------------------------------------------------------------->

            <select
                name="assignment"
                onchange="this.form.submit()"
            >

                <option value="">
                    All Issues
                </option>

                <option
                    value="assigned"
                    <?= $assignmentFilter === 'assigned' ? 'selected' : '' ?>
                >
                    Assigned
                </option>

                <option
                    value="unassigned"
                    <?= $assignmentFilter === 'unassigned' ? 'selected' : '' ?>
                >
                    Unassigned
                </option>

            </select>

<!-- FILTER DATES ------------------------------------------------------------------------------------------------------------------------------------------------------------>

            <select
                name="sort"
                onchange="this.form.submit()"
            >

                <option
                    value="newest"
                    <?= $sort === 'newest' ? 'selected' : '' ?>
                >
                    Newest Created
                </option>

                <option
                    value="oldest"
                    <?= $sort === 'oldest' ? 'selected' : '' ?>
                >
                    Oldest Created
                </option>

                <option
                    value="updated"
                    <?= $sort === 'updated' ? 'selected' : '' ?>
                >
                    Recently Updated
                </option>

            </select>

        </form>

    </div>

<br>

<!-- ISSUE LIST ------------------------------------------------------------------------------------------------------------------------------------------------------------->

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
                    <span class="status-<?= strtolower(str_replace(
                        ' ',
                        '-',
                        $statusMap[$issue['status_id']] ?? 'unknown'
                    )) ?>">
                        <?= htmlspecialchars(
                            $statusMap[$issue['status_id']] ?? 'Unknown'
                        ) ?>
                    </span>

                    <span class="separator">
                        •
                    </span>

                    <span class="priority-<?= strtolower(
                        $severityMap[$issue['severity_id']] ?? 'unknown'
                    ) ?>">
                        <?= htmlspecialchars(
                            $severityMap[$issue['severity_id']] ?? 'Unknown'
                        ) ?>
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

<!-- CREATE ISSUE MODAL ------------------------------------------------------------------------------------------------------------------------------------------------------->

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

<!-- MODAL SCRIPT ------------------------------------------------------------------------------------------------------------------------------------------------------------->

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

<?php require '../app/views/partials/footer.php'; ?>