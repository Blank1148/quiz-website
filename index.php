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

<style>
    /* Index Page Specific Styles */
    p {
        margin-bottom: 1rem;
        font-size: 1rem;
        color: #34495e;
        text-align: center;
        max-width: 800px;
        margin-left: auto;
        margin-right: auto;
    }

    .intro-container {
        background-color: #fff;
        padding: 2rem;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        max-width: 800px;
        margin: 2rem auto;
    }

    h3 {
        color: #34495e;
        margin: 1.5rem 0 1rem;
        font-size: 1.4rem;
        text-align: center;
    }

    ul {
        list-style-type: disc;
        margin: 1rem auto;
        padding-left: 2rem;
        max-width: 600px;
    }

    li {
        margin-bottom: 0.5rem;
        font-size: 1rem;
        color: #34495e;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .intro-container {
            padding: 1rem;
        }

        p {
            font-size: 0.9rem;
        }

        ul {
            padding-left: 1.5rem;
        }
    }
</style>

<h2>Welcome to the Quiz Website</h2>
<div class="intro-container">
    <p>The Quiz Website is an interactive platform designed to challenge and enhance your knowledge across various topics. Users can access quizzes by entering unique codes provided by administrators, ensuring secure and controlled access to diverse quiz categories.</p>
    <h3>Key Features</h3>
    <ul>
        <li>Secure user registration and role-based access for users and admins.</li>
        <li>Unique quiz codes for accessing specific quizzes created by administrators.</li>
        <li>Instant feedback and score tracking for completed quizzes.</li>
        <li>Admin panel for creating, editing, and managing quiz categories and questions.</li>
    </ul>
    <p>Join today to test your knowledge, track your progress, and explore a variety of engaging quizzes!</p>
</div>

<?php include 'includes/footer.php'; ?>