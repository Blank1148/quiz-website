<?php
include 'includes/db.php';
include 'includes/header.php';

$error = '';
$results = [];

if(isset($_POST['fetch_result'])){
    $username = trim($_POST['username']);
    $quiz_code = trim($_POST['quiz_code']);

    if(!empty($username) && !empty($quiz_code)){
        // Fetch user_id and category_id
        $stmt = $pdo->prepare("SELECT u.id AS user_id, c.id AS category_id, c.name AS category_name FROM users u JOIN categories c ON c.quiz_code = ? WHERE u.username = ?");
        $stmt->execute([$quiz_code, $username]);
        $ids = $stmt->fetch(PDO::FETCH_ASSOC);

        if($ids){
            // Fetch results
            $stmt = $pdo->prepare("SELECT score, created_at FROM results WHERE user_id = ? AND category_id = ? ORDER BY created_at DESC");
            $stmt->execute([$ids['user_id'], $ids['category_id']]);
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $category_name = $ids['category_name'];
        } else {
            $error = "Invalid username or quiz code.";
        }
    } else {
        $error = "Please enter username and quiz code.";
    }
}
?>

<h2>Fetch Quiz Result</h2>

<?php if($error): ?>
<p style="color:red;"><?php echo $error; ?></p>
<?php endif; ?>

<form method="post" action="">
    <p>
        <label>Username:</label><br>
        <input type="text" name="username" required>
    </p>
    <p>
        <label>Quiz Code:</label><br>
        <input type="text" name="quiz_code" required>
    </p>
    <input type="submit" name="fetch_result" value="Fetch Result">
</form>

<?php if(!empty($results)): ?>
    <h3>Results for <?php echo htmlspecialchars($username); ?> in <?php echo htmlspecialchars($category_name); ?> (Code: <?php echo htmlspecialchars($quiz_code); ?>)</h3>
    <table border="1" cellpadding="5" cellspacing="0">
        <tr>
            <th>Score</th>
            <th>Date</th>
        </tr>
        <?php foreach($results as $result): ?>
            <tr>
                <td><?php echo $result['score']; ?></td>
                <td><?php echo $result['created_at']; ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php elseif(isset($_POST['fetch_result'])): ?>
    <p>No results found for this user and quiz.</p>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>