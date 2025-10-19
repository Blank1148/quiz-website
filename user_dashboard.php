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

<h2>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></h2>
<p><a href="logout.php">Logout</a></p>

<h3>Enter Quiz Code to Start a Quiz</h3>
<form action="quiz.php" method="get">
    <input type="text" name="code" placeholder="Quiz Code" required>
    <input type="submit" value="Start Quiz">
</form>

<h3>Your Quiz Results</h3>
<?php if(count($results) > 0): ?>
    <table border="1" cellpadding="5" cellspacing="0">
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