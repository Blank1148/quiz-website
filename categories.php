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

<h2>Enter Quiz Code</h2>
<p>Enter the quiz code provided by the admin to start the quiz:</p>

<form action="quiz.php" method="get">
    <input type="text" name="code" placeholder="Quiz Code" required>
    <input type="submit" value="Start Quiz">
</form>

<?php include 'includes/footer.php'; ?>