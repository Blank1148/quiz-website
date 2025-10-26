<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz Website</title>
    <style>
        /* Reset default styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            line-height: 1.6;
            background-color: #f4f4f9;
            color: #333;
        }

        /* Header Styles */
        header {
            background-color: #2c3e50;
            color: #fff;
            padding: 1rem;
            text-align: center;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        header h1 {
            font-size: 2rem;
            margin-bottom: 0.5rem;
        }

        nav {
            margin-top: 0.5rem;
        }

        nav a {
            color: #fff;
            text-decoration: none;
            margin: 0 1rem;
            font-weight: bold;
            transition: color 0.3s;
        }

        nav a:hover {
            color: #3498db;
        }

        /* Main Content */
        main {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 1rem;
        }

        h2 {
            color: #2c3e50;
            margin-bottom: 1rem;
            font-size: 1.8rem;
            text-align: center;
        }

        /* Links */
        a {
            color: #3498db;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            header h1 {
                font-size: 1.5rem;
            }

            nav a {
                margin: 0 0.5rem;
                font-size: 0.9rem;
            }

            main {
                padding: 0 0.5rem;
            }
        }
    </style>
</head>
<body>
    <header>
        <h1>Quiz Website</h1>
        <nav>
            <?php if(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true): ?>
                <?php if($_SESSION['role'] === 'admin'): ?>
                    <a href="admin.php">Admin Dashboard</a> |
                    <a href="logout.php">Logout</a>
                <?php else: ?>
                    <a href="user_dashboard.php">Dashboard</a> |
                    <a href="result_fetch.php">Fetch Result</a> |
                    <a href="logout.php">Logout</a>
                <?php endif; ?>
            <?php else: ?>
                <a href="index.php">Home</a> |
                <a href="about.php">About</a> |
                <a href="result_fetch.php">Fetch Result</a> |
                <a href="login.php">Login</a> |
                <a href="register.php">Register</a>
            <?php endif; ?>
        </nav>
    </header>
    <main>