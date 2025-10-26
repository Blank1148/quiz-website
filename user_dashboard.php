<?php
session_start();
include 'includes/db.php';
include 'includes/header.php';

// Check if logged in and is user
if(!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || $_SESSION['role'] !== 'user'){
    header("Location: login.php");
    exit;
}

// Fetch user's results
$stmt = $pdo->prepare("SELECT r.score, r.created_at, c.name, c.quiz_code FROM results r JOIN categories c ON r.category_id = c.id WHERE r.user_id = ? ORDER BY r.created_at DESC");
$stmt->execute([$_SESSION['user_id']]);
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<style>
    /* User Dashboard Page Specific Styles */
    form {
        background-color: #fff;
        padding: 2rem;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        max-width: 600px;
        margin: 0 auto 2rem;
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

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 1rem;
        background-color: #fff;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        border-radius: 8px;
        overflow: hidden;
    }

    th, td {
        padding: 0.8rem;
        text-align: left;
        border-bottom: 1px solid #ddd;
    }

    th {
        background-color: #34495e;
        color: #fff;
        font-weight: bold;
    }

    tr:nth-child(even) {
        background-color: #f9f9f9;
    }

    tr:hover {
        background-color: #f1f1f1;
    }

    p {
        margin-bottom: 1rem;
        font-size: 1rem;
        color: #34495e;
        text-align: center;
    }

    h3 {
        color: #34495e;
        margin: 1.5rem 0 1rem;
        font-size: 1.4rem;
        text-align: center;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        form {
            padding: 1rem;
        }

        table {
            font-size: 0.9rem;
        }

        th, td {
            padding: 0.5rem;
        }
    }
</style>

<h2>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></h2>

<h3>Enter Quiz Code to Start a Quiz</h3>
<form action="quiz.php" method="get">
    <input type="text" name="code" placeholder="Quiz Code" required>
    <input type="submit" value="Start Quiz">
</form>

<h3>Your Quiz Results</h3>
<?php if(count($results) > 0): ?>
    <table>
        <tr>
            <th>Quiz Name</th>
            <th>Quiz Code</th>
            <th>Score</th>
            <th>Date</th>
        </tr>
        <?php foreach($results as $result): ?>
            <tr>
                <td><?php echo htmlspecialchars($result['name']); ?></td>
                <td><?php echo htmlspecialchars($result['quiz_code']); ?></td>
                <td><?php echo $result['score']; ?></td>
                <td><?php echo $result['created_at']; ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php else: ?>
    <p>No results yet. Take a quiz to see your scores here.</p>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>