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

    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    $user = $repo->findByEmail($email);

    if (
        $user &&
        password_verify(
            $password,
            $user['password']
        )
    ) {

        $_SESSION['user'] = $user;

        header('Location: index.php');
        exit;

    }

    $error = 'Invalid email or password.';
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
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
        Zaloguj sie
    </p>

    <?php if (!empty($error)): ?>

        <div class="error-message">
            <?= htmlspecialchars($error) ?>
        </div>

    <?php endif; ?>

    <form method="POST">

        <div class="form-group">

            <label>Email</label>

            <input
                type="email"
                name="email"
                required
            >

        </div>

        <div class="form-group">

            <label>Password</label>

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

        <button
            type="submit"
            class="auth-btn"
        >
            Login
        </button>

    </form>

    <div class="auth-link">

        Don't have an account?

        <a href="register.php">
            Register here
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
</html>