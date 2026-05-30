<?php

session_start();

if (!isset($_SESSION['user'])) {

    header('Location: login.php');
    exit;
}

require '../app/views/partials/header.php';

$userName = $_SESSION['user']['first_name'];

require_once '../app/models/IssueRepository.php';

$repo = new IssueRepository();

$issues = $repo->getAll();

$currentUserId = $_SESSION['user']['id'];

$reportedIssues = array_filter(
    $issues,
    fn($issue) =>
        ($issue['reporter'] ?? '') === $currentUserId
);

?>
<head>
    <link
    rel="stylesheet"
    href="/Issue-Tracker/public/assets/css/dashboard.css"
>
</head>

<title>Dashboard</title>

<div class="dashboard-container">

    <h1>
        Welcome, <?= htmlspecialchars($userName) ?>
    </h1>

    <div class="dashboard-grid">

        <div class="dashboard-card">

            <h2>
                Reported By Me
            </h2>

            <?php if (empty($reportedIssues)): ?>

    <p>
        No issues found.
    </p>

<?php else: ?>

    <?php foreach ($reportedIssues as $issue): ?>

        <div class="dashboard-issue">

            <a href="issue.php?id=<?= htmlspecialchars($issue['id']) ?>">

                <?= htmlspecialchars($issue['id']) ?>

            </a>

            -

            <?= htmlspecialchars($issue['summary']) ?>

        </div>

    <?php endforeach; ?>

<?php endif; ?>

        </div>

        <div class="dashboard-card">

            <h2>
                Assigned To Me
            </h2>

            <p>
                No issues found.
            </p>

        </div>

    </div>

</div>

<?php require '../app/views/partials/footer.php'; ?>