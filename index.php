<?php
session_start();
if(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true){
    if($_SESSION['role'] === 'admin'){
        header("Location: admin.php");
    } else {
        header("Location: user_dashboard.php");
    }
    exit;
}
include 'includes/header.php';
?>

<h2>Welcome to Quiz Website</h2>
<p>Please <a href="login.php">login</a> or <a href="register.php">register</a> to take quizzes.</p>
<p>To fetch a specific result, <a href="result_fetch.php">click here</a>.</p>

<?php include 'includes/footer.php'; ?>