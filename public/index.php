<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Issue Tracker</title>

    <style>

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f4f5f7;
        }

        .hero {
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .hero-box {
            background: white;
            padding: 50px;
            border-radius: 12px;
            text-align: center;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            width: 90%;
            max-width: 500px;
        }

        h1 {
            margin-top: 0;
        }

        p {
            color: #666;
            margin-bottom: 30px;
        }

        .btn {
            display: inline-block;
            background: #0052cc;
            color: white;
            padding: 12px 20px;
            border-radius: 6px;
            text-decoration: none;
            margin: 5px;
        }

    </style>

</head>
<body>

<div class="hero">

    <div class="hero-box">

        <h1>Issue Tracker</h1>

        <p>
            A lightweight system for managing software issues.
        </p>

        <a
            href="issues.php"
            class="btn"
        >
            Show Issues
        </a>

    </div>

</div>

</body>
</html>