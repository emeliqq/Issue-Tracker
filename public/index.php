<?php

session_start();

if (!isset($_SESSION['user'])) {

    header('Location: login.php');
    exit;
}

 require '../app/views/partials/header.php'; 

    require_once '../app/models/IssueRepository.php';

    $repo = new IssueRepository();

    $issues = array_reverse($repo->getAll());

    $recentIssues = array_slice($issues, 0, 4);

?>

<title>Issue Tracker</title>

<link
    rel="stylesheet"
    href="assets/css/home.css"
>

</head>
<body>



<div class="hero-layout">

<div class="hero">

    <div class="hero-content">

        <h1>
            Simple issue tracking system
        </h1>

        <p>
            Manage software bugs, QA reports and project issues
            in one place.
        </p>

        <div class="hero-buttons">

            <a
                href="issues.php"
                class="primary-btn"
            >
                View Issues
            </a>

        </div>

        <!-- RECENT ISSUES -->

        <div class="recent-section">

            <h3>
                Recent Issues
            </h3>

            <?php if (empty($recentIssues)): ?>

                <div class="recent-card">

                    <p>
                        No recent issues
                    </p>

                </div>

            <?php else: ?>

                <?php foreach ($recentIssues as $issue): ?>

                    <a
                        href="issue.php?id=<?= htmlspecialchars($issue['id']) ?>"
                        class="recent-card-link"
                    >

                        <div class="recent-card">

                            <div class="recent-header">

                                <strong>
                                    <?= htmlspecialchars($issue['id']) ?>
                                </strong>

                            </div>

                            <div class="recent-meta">

                                <?php

                                $status = strtolower($issue['status'] ?? 'open');
                                $priority = strtolower($issue['priority'] ?? 'low');

                                ?>

                                <span class="status-badge <?= $status ?>">
                                    <?= htmlspecialchars($issue['status'] ?? 'Open') ?>
                                </span>

                                <span class="separator">
                                    •
                                </span>

                                <span class="severity-badge <?= $priority ?>">
                                    <?= htmlspecialchars($issue['priority'] ?? 'Low') ?>
                                </span>

                            </div>

                            <p title="<?= htmlspecialchars($issue['summary']) ?>">
                                <?= htmlspecialchars(
                                    strlen($issue['summary']) > 80
                                        ? substr($issue['summary'], 0, 80) . '...'
                                        : $issue['summary']
                                ) ?>
                            </p>

                        </div>

                    </a>

                <?php endforeach; ?>

            <?php endif; ?>

        </div>

    </div>

</div>

<div class="info-section">

    <div class="info-card">

        <h2>
            Instruction how to write an issue
        </h2>

        <div class="example-form">
            <h3>Summary</h3>
    
            <div class="example-group small-box">

                *Short summary of what happened*

            </div>
            <h3>Description</h3>
            <div class="example-group large-box">

                *Describe what happened in details as much as it is possible*

                <br><br>

                Expected result:

                <br>

                *Describe what should happen*

                <br><br>

                Note: (or Notes)

                <br>

                *Write additional information that can be useful*

                <br>

                1. First note.

                <br>

                2. Second note.

            </div>
            <h3>Steps to reproduce</h3>
            <div class="example-group medium-box">

                *Describe in steps what should you do to make that issue happen again*

                <br><br>

                1. First step

                <br>

                2. Second step

            </div>
            <h3>Priority</h3>
            <div class="example-group small-box">

                <select disabled>

                    <option>
                        Low
                    </option>

                </select>

                <br><br>

                Remember to select appropriate priority of the issue

            </div>

        </div>

    </div>

</div>


</div>

<?php require '../app/views/partials/footer.php'; ?>
