<?php

$userName = $_SESSION['user']['first_name'] ?? '';

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <link
            rel="stylesheet"
            href="/Issue-Tracker/public/assets/css/global.css"
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

                <div class="user-menu">

                    <div class="user-trigger">
                        👤 <?= htmlspecialchars($userName) ?>
                    </div>

                    <div class="user-dropdown">

                        <a href="dashboard.php">
                            Dashboard
                        </a>

                        <a href="logout.php">
                            Logout
                        </a>

                    </div>
        </div>

    </header>