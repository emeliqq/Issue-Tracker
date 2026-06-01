<?php

session_start();

if (isset($_SESSION['user'])) {

    header('Location: index.php');
    exit;
}

require_once '../app/models/UserRepository.php';

$repo = new UserRepository();

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $firstName = trim($_POST['first_name'] ?? '');
    $lastName = trim($_POST['last_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';

    if (
        empty($firstName) ||
        empty($lastName) ||
        empty($email) ||
        empty($password) ||
        empty($confirmPassword)
    ) {

        $error = 'All fields are required.';
    }

    elseif ($password !== $confirmPassword) {

        $error = 'Passwords do not match.';
    }

    elseif ($repo->findByEmail($email)) {

        $error = 'User with this email already exists.';
    }

    else {

        $newUser = [
            'id' => $repo->getNextId(),
            'first_name' => $firstName,
            'last_name' => $lastName,
            'email' => $email,
            'password' => password_hash(
                $password,
                PASSWORD_DEFAULT
            ),
            'created_at' => date('Y-m-d H:i:s')
        ];

        $repo->add($newUser);

        header('Location: login.php');
        exit;
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link
        rel="stylesheet"
        href="/public/assets/css/auth.css"
    >
</head>

<body>

<div class="auth-card">

    <h1 class="auth-title">
        Issue Tracker
    </h1>

    <p class="auth-subtitle">
        Create your account
    </p>

    <?php if (!empty($error)): ?>

        <div class="error-message">
            <?= htmlspecialchars($error) ?>
        </div>

    <?php endif; ?>

    <form method="POST">

        <div class="form-group">

            <label>
                First Name
            </label>

            <input
                type="text"
                name="first_name"
                required
            >

        </div>

        <div class="form-group">

            <label>
                Last Name
            </label>

            <input
                type="text"
                name="last_name"
                required
            >

        </div>

        <div class="form-group">

            <label>
                Email
            </label>

            <input
                type="email"
                name="email"
                required
            >

        </div>

        <div class="form-group">

            <label>
                Password
            </label>

            <div class="password-wrapper">

    <input
        type="password"
        id="password"
        name="password"
        required
    >

    <button
        type="button"
        class="toggle-password"
        onclick="togglePassword('password', this)"
    >
        Show
    </button>

</div>

        </div>

        <div class="form-group">

            <label>
                Confirm Password
            </label>

            <div class="password-wrapper">

    <input
        type="password"
        id="confirm_password"
        name="confirm_password"
        required
    >

    <button
        type="button"
        class="toggle-password"
        onclick="togglePassword('confirm_password', this)"
    >
        Show
    </button>

</div>

        <button
            type="submit"
            class="auth-btn"
        >
            Register
        </button>

    </form>

    <div class="auth-link">

        Already have an account?

        <a href="login.php">
            Login here
        </a>

    </div>

</div>
<script>

function togglePassword(id, button)
{
    const field = document.getElementById(id);

    if (field.type === 'password') {

        field.type = 'text';

        button.textContent = 'Hide';

    } else {

        field.type = 'password';

        button.textContent = 'Show';
    }
}

</script>

</body>

</body>
</html>