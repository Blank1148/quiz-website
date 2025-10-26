<?php
session_start();
include 'includes/db.php';
include 'includes/header.php';

// Check if logged in and is admin
if(!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || $_SESSION['role'] !== 'admin'){
    echo "<p>You must <a href='login.php'>login</a> as admin to access the admin panel.</p>";
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

// Fetch category info and check ownership
$stmt = $pdo->prepare("SELECT * FROM categories WHERE id = ? AND creator_id = ?");
$stmt->execute([$category_id, $_SESSION['user_id']]);
$category = $stmt->fetch(PDO::FETCH_ASSOC);
if(!$category){
    echo "<p>Category not found or you do not have permission.</p>";
    include 'includes/footer.php';
    exit;
}

// Handle adding a new question
if(isset($_POST['add_question'])){
    $question_text = trim($_POST['question_text']);
    $correct_answer = trim($_POST['correct_answer']);
    $options = $_POST['options']; // array of answer options

    if(!empty($question_text) && !empty($correct_answer) && count(array_filter($options)) >= 2){
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

<style>
    /* Manage Questions Page Specific Styles */
    form {
        background-color: #fff;
        padding: 2rem;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        max-width: 600px;
        margin: 0 auto 2rem;
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

    td a {
        color: #3498db;
        margin-right: 0.5rem;
    }

    td a:hover {
        text-decoration: underline;
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

        table {
            font-size: 0.9rem;
        }

        th, td {
            padding: 0.5rem;
        }

        .back-link {
            padding: 0.5rem 1rem;
            font-size: 0.9rem;
        }
    }
</style>

<h2>Manage Questions for: <?php echo htmlspecialchars($category['name']); ?> (Code: <?php echo htmlspecialchars($category['quiz_code']); ?>)</h2>
<p><a href="admin.php" class="back-link">Back to Admin Dashboard</a></p>

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
    <table>
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