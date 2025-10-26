<?php
include 'includes/header.php';
?>

<style>
    /* About Page Specific Styles */
    p {
        margin-bottom: 1rem;
        font-size: 1rem;
        color: #34495e;
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

    .back-link {
        display: inline-block;
        margin-top: 1.5rem;
        padding: 0.6rem 1.2rem;
        background-color: #3498db;
        color: #fff;
        border-radius: 4px;
        text-align: center;
        font-weight: bold;
        transition: background-color 0.3s;
    }

    .back-link:hover {
        background-color: #2980b9;
        text-decoration: none;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        ul {
            padding-left: 1.5rem;
        }

        .back-link {
            padding: 0.5rem 1rem;
            font-size: 0.9rem;
        }
    }
</style>

<h2>About the Quiz / Trivia Website</h2>

<p>Welcome to our Quiz / Trivia Website! This platform allows users to test their knowledge across multiple categories. You can select a category, answer questions, and see your score immediately.</p>

<p>Features of this website:</p>
<ul>
    <li>Multiple quiz categories like General Knowledge, Science, History, etc.</li>
    <li>Interactive quizzes with multiple-choice questions.</li>
    <li>Instant feedback and score calculation.</li>
    <li>Admin panel for managing categories, questions, and answers.</li>
</ul>

<p>Have fun testing your knowledge and challenge yourself with new quizzes every time!</p>

<a href="index.php" class="back-link">Back to Home</a>

<?php
include 'includes/footer.php';
?>