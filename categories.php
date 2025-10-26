<?php
session_start();
include 'includes/db.php';
include 'includes/header.php';

// Check if logged in
if(!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true){
    header("Location: login.php");
    exit;
}
?>

<style>
    /* Categories Page Specific Styles */
    form {
        background-color: #fff;
        padding: 2rem;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        max-width: 600px;
        margin: 0 auto;
    }

    form p {
        margin-bottom: 1rem;
    }

    form input[type="text"] {
        width: 100%;
        padding: 0.8rem;
        border: 1px solid #ccc;
        border-radius: 4px;
        font-size: 1rem;
        transition: border-color 0.3s;
    }

    form input[type="text"]:focus {
        border-color: #3498db;
        outline: none;
    }

    form input[type="submit"] {
        background-color: #3498db;
        color: #fff;
        border: none;
        padding: 0.8rem 1.5rem;
        border-radius: 4px;
        cursor: pointer;
        font-size: 1rem;
        transition: background-color 0.3s;
    }

    form input[type="submit"]:hover {
        background-color: #2980b9;
    }

    p {
        margin-bottom: 1rem;
        font-size: 1rem;
        color: #34495e;
        text-align: center;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        form {
            padding: 1rem;
        }
    }
</style>

<h2>Enter Quiz Code</h2>
<p>Enter the quiz code provided by the admin to start the quiz:</p>

<form action="quiz.php" method="get">
    <p>
        <input type="text" name="code" placeholder="Quiz Code" required>
    </p>
    <input type="submit" value="Start Quiz">
</form>

<?php include 'includes/footer.php'; ?>