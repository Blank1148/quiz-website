<?php
session_start();
include 'includes/db.php';
include 'includes/header.php';

// Check if logged in
if(!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true){
    header("Location: login.php");
    exit;
}

// Check if code is provided
if(!isset($_GET['code']) || empty($_GET['code'])){
    echo "<p>Invalid quiz code. Please enter a valid code.</p>";
    include 'includes/footer.php';
    exit;
}

$code = trim($_GET['code']);

// Fetch category by code
$stmt = $pdo->prepare("SELECT * FROM categories WHERE quiz_code = ?");
$stmt->execute([$code]);
$category = $stmt->fetch(PDO::FETCH_ASSOC);

if(!$category){
    echo "<p>Invalid quiz code.</p>";
    include 'includes/footer.php';
    exit;
}

$category_id = $category['id'];

// Fetch all questions for this category
$stmt = $pdo->prepare("SELECT * FROM questions WHERE category_id = ?");
$stmt->execute([$category_id]);
$questions = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch all answers for questions
$answers_stmt = $pdo->prepare("SELECT * FROM answers WHERE question_id = ?");
?>

<style>
    /* Quiz Page Specific Styles */
    form {
        background-color: #fff;
        padding: 2rem;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        max-width: 800px;
        margin: 0 auto;
    }

    .question-block {
        margin-bottom: 1.5rem;
        padding: 1rem;
        border: 1px solid #ddd;
        border-radius: 4px;
        background-color: #f9f9f9;
    }

    .question-block p {
        margin-bottom: 0.5rem;
        font-weight: bold;
    }

    .question-block div {
        margin: 0.5rem 0;
    }

    .question-block input[type="radio"] {
        margin-right: 0.5rem;
    }

    .question-block label {
        font-size: 1rem;
        color: #34495e;
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
        display: block;
        margin: 2rem auto 0;
    }

    form input[type="submit"]:hover {
        background-color: #2980b9;
    }

    hr {
        border: 0;
        border-top: 1px solid #ddd;
        margin: 1rem 0;
    }

    p {
        margin-bottom: 1rem;
        font-size: 1rem;
        color: #34495e;
        text-align: center;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        form {
            padding: 1rem;
        }

        .question-block {
            padding: 0.8rem;
        }
    }
</style>

<h2><?php echo htmlspecialchars($category['name']); ?> Quiz (Code: <?php echo htmlspecialchars($code); ?>)</h2>

<?php if(count($questions) > 0): ?>
<form action="results.php" method="post">
    <input type="hidden" name="category_id" value="<?php echo $category_id; ?>">
    <?php foreach($questions as $index => $question): ?>
        <div class="question-block">
            <p><strong>Q<?php echo $index+1; ?>: <?php echo htmlspecialchars($question['question_text']); ?></strong></p>
            <?php 
                $answers_stmt->execute([$question['id']]);
                $answers = $answers_stmt->fetchAll(PDO::FETCH_ASSOC);
            ?>
            <?php foreach($answers as $answer): ?>
                <div>
                    <input type="radio" name="answers[<?php echo $question['id']; ?>]" value="<?php echo htmlspecialchars($answer['answer_text']); ?>" required>
                    <label><?php echo htmlspecialchars($answer['answer_text']); ?></label>
                </div>
            <?php endforeach; ?>
        </div>
        <hr>
    <?php endforeach; ?>
    <input type="submit" value="Submit Quiz">
</form>
<?php else: ?>
    <p>No questions available in this category.</p>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>