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

<style>
    /* Result Fetch Page Specific Styles */
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

    form label {
        display: block;
        font-weight: bold;
        margin-bottom: 0.5rem;
        color: #34495e;
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

    p[style*="color:red"] {
        background-color: #ffe6e6;
        padding: 0.8rem;
        border-radius: 4px;
        margin-bottom: 1rem;
        text-align: center;
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
    <table>
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