<?php require '../app/views/partials/header.php'; ?>
<?php

require_once '../app/models/IssueRepository.php';

$repo = new IssueRepository();

$search = trim($_GET['search'] ?? '');
$sort = $_GET['sort'] ?? 'newest';

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



    
    <header class="topbar">

    <a
        href="index.php"
        class="logo"
    >
        Issue Tracker
    </a>

    <div class="nav-links">

        <a href="index.php">
            Home
        </a>

        <a href="issues.php">
            Issues
        </a>

    </div>

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

    <div class="toolbar">

            <form method="GET" class="filters-form">

                <input
                    type="text"
                    name="search"
                    placeholder="Search keywords..."
                    value="<?= htmlspecialchars($search) ?>"
                >

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

                <button type="submit">
                    Search
                </button>

            </form>

        </div>
<br>
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
                    <span class="status-<?= strtolower(str_replace(' ', '-', $issue['status'])) ?>">
                        <?= htmlspecialchars($issue['status']) ?>
                    </span>

                    <span class="separator">
                                    •
                    </span>

                    <span class="priority-<?= strtolower($issue['priority']) ?>">
                        <?= htmlspecialchars($issue['priority']) ?>
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

<?php require '../app/views/partials/footer.php'; ?>