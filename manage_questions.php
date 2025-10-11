<?php
session_start();
include 'includes/db.php';
include 'includes/header.php';

// Check admin login
if(!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true){
    echo "<p>You must <a href='login.php'>login</a> to access the admin panel.</p>";
    include 'includes/footer.php';
    exit;
}

// Check category_id
if(!isset($_GET['category_id']) || empty($_GET['category_id'])){
    echo "<p>No category selected.</p>";
    include 'includes/footer.php';
    exit;
}

$category_id = intval($_GET['category_id']);

// Fetch category info
$stmt = $pdo->prepare("SELECT * FROM categories WHERE id = ?");
$stmt->execute([$category_id]);
$category = $stmt->fetch(PDO::FETCH_ASSOC);
if(!$category){
    echo "<p>Category not found.</p>";
    include 'includes/footer.php';
    exit;
}

// Handle adding a new question
if(isset($_POST['add_question'])){
    $question_text = trim($_POST['question_text']);
    $correct_answer = trim($_POST['correct_answer']);
    $options = $_POST['options']; // array of answer options

    if(!empty($question_text) && !empty($correct_answer) && count($options) >= 2){
        // Insert question
        $stmt = $pdo->prepare("INSERT INTO questions (category_id, question_text, correct_answer) VALUES (?, ?, ?)");
        $stmt->execute([$category_id, $question_text, $correct_answer]);
        $question_id = $pdo->lastInsertId();

        // Insert answers
        foreach($options as $option){
            if(!empty($option)){
                $stmt = $pdo->prepare("INSERT INTO answers (question_id, answer_text) VALUES (?, ?)");
                $stmt->execute([$question_id, $option]);
            }
        }
        echo "<p style='color:green;'>Question added successfully!</p>";
    }
}

// Handle deleting a question
if(isset($_GET['delete_question'])){
    $qid = intval($_GET['delete_question']);
    $stmt = $pdo->prepare("DELETE FROM questions WHERE id = ?");
    $stmt->execute([$qid]);
    echo "<p style='color:red;'>Question deleted successfully!</p>";
}

// Fetch all questions for this category
$stmt = $pdo->prepare("SELECT * FROM questions WHERE category_id = ? ORDER BY id DESC");
$stmt->execute([$category_id]);
$questions = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<h2>Manage Questions for: <?php echo htmlspecialchars($category['name']); ?></h2>
<p><a href="admin.php">Back to Admin Dashboard</a></p>

<h3>Add New Question</h3>
<form method="post" action="">
    <p>
        <label>Question:</label><br>
        <textarea name="question_text" rows="3" cols="50" required></textarea>
    </p>
    <p>
        <label>Correct Answer:</label><br>
        <input type="text" name="correct_answer" required>
    </p>
    <p>
        <label>Other Options (minimum 1, separate inputs for each):</label><br>
        <input type="text" name="options[]" required>
        <input type="text" name="options[]">
        <input type="text" name="options[]">
        <input type="text" name="options[]">
    </p>
    <input type="submit" name="add_question" value="Add Question">
</form>

<h3>Existing Questions</h3>
<?php if(count($questions) > 0): ?>
    <table border="1" cellpadding="5" cellspacing="0">
        <tr>
            <th>ID</th>
            <th>Question</th>
            <th>Correct Answer</th>
            <th>Actions</th>
        </tr>
        <?php foreach($questions as $q): ?>
            <tr>
                <td><?php echo $q['id']; ?></td>
                <td><?php echo htmlspecialchars($q['question_text']); ?></td>
                <td><?php echo htmlspecialchars($q['correct_answer']); ?></td>
                <td>
                    <a href="edit_question.php?question_id=<?php echo $q['id']; ?>">Edit</a> |
                    <a href="?category_id=<?php echo $category_id; ?>&delete_question=<?php echo $q['id']; ?>" onclick="return confirm('Delete this question?')">Delete</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php else: ?>
    <p>No questions added yet.</p>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>
