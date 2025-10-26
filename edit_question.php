<?php
session_start();
include 'includes/db.php';
include 'includes/header.php';

// Check if logged in and is admin
if(!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || $_SESSION['role'] !== 'admin'){
    echo "<p style='color:red;'>You must <a href='login.php'>login</a> as admin to access this page.</p>";
    include 'includes/footer.php';
    exit;
}

// Get question ID
if(!isset($_GET['question_id']) || empty($_GET['question_id'])){
    echo "<p>No question selected.</p>";
    include 'includes/footer.php';
    exit;
}

$question_id = intval($_GET['question_id']);

// Fetch question and check category ownership
$stmt = $pdo->prepare("SELECT q.*, c.creator_id FROM questions q JOIN categories c ON q.category_id = c.id WHERE q.id = ?");
$stmt->execute([$question_id]);
$question = $stmt->fetch(PDO::FETCH_ASSOC);

if(!$question || $question['creator_id'] != $_SESSION['user_id']){
    echo "<p>Question not found or you do not have permission.</p>";
    include 'includes/footer.php';
    exit;
}

// Fetch answers
$stmt = $pdo->prepare("SELECT * FROM answers WHERE question_id = ?");
$stmt->execute([$question_id]);
$answers = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Handle update form submission
if(isset($_POST['update_question'])){
    $question_text = trim($_POST['question_text']);
    $correct_answer = trim($_POST['correct_answer']);
    $options = $_POST['options'];

    if(!empty($question_text) && !empty($correct_answer)){
        // Update question
        $stmt = $pdo->prepare("UPDATE questions SET question_text = ?, correct_answer = ? WHERE id = ?");
        $stmt->execute([$question_text, $correct_answer, $question_id]);

        // Delete old answers
        $pdo->prepare("DELETE FROM answers WHERE question_id = ?")->execute([$question_id]);

        // Insert new answers
        foreach($options as $option){
            if(!empty($option)){
                $stmt = $pdo->prepare("INSERT INTO answers (question_id, answer_text) VALUES (?, ?)");
                $stmt->execute([$question_id, $option]);
            }
        }

        echo "<p style='color:green;'>Question updated successfully!</p>";
        // Refresh answers
        $stmt = $pdo->prepare("SELECT * FROM answers WHERE question_id = ?");
        $stmt->execute([$question_id]);
        $answers = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>

<style>
    /* Edit Question Page Specific Styles */
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

    form textarea,
    form input[type="text"] {
        width: 100%;
        padding: 0.8rem;
        border: 1px solid #ccc;
        border-radius: 4px;
        font-size: 1rem;
        transition: border-color 0.3s;
    }

    form textarea:focus,
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

    p[style*="color:red"] {
        background-color: #ffe6e6;
        padding: 0.8rem;
        border-radius: 4px;
        margin-bottom: 1rem;
        text-align: center;
    }

    p[style*="color:green"] {
        background-color: #e6ffed;
        padding: 0.8rem;
        border-radius: 4px;
        margin-bottom: 1rem;
        text-align: center;
    }

    .back-link {
        display: inline-block;
        margin-bottom: 1rem;
        padding: 0.6rem 1.2rem;
        background-color: #3498db;
        color: #fff;
        border-radius: 4px;
        font-weight: bold;
        transition: background-color 0.3s;
    }

    .back-link:hover {
        background-color: #2980b9;
        text-decoration: none;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        form {
            padding: 1rem;
        }

        .back-link {
            padding: 0.5rem 1rem;
            font-size: 0.9rem;
        }
    }
</style>

<h2>Edit Question</h2>
<p><a href="admin.php" class="back-link">Back to Admin Dashboard</a></p>

<form method="post" action="">
    <p>
        <label>Question:</label><br>
        <textarea name="question_text" rows="3" cols="50" required><?php echo htmlspecialchars($question['question_text']); ?></textarea>
    </p>

    <p>
        <label>Correct Answer:</label><br>
        <input type="text" name="correct_answer" value="<?php echo htmlspecialchars($question['correct_answer']); ?>" required>
    </p>

    <p>
        <label>Answer Options:</label><br>
        <?php foreach($answers as $a): ?>
            <input type="text" name="options[]" value="<?php echo htmlspecialchars($a['answer_text']); ?>"><br>
        <?php endforeach; ?>
        <!-- Extra blank fields to add new options -->
        <input type="text" name="options[]" placeholder="Add new option"><br>
        <input type="text" name="options[]" placeholder="Add new option"><br>
    </p>

    <input type="submit" name="update_question" value="Update Question">
</form>

<?php include 'includes/footer.php'; ?>