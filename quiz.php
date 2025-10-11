<?php
include 'includes/db.php';
include 'includes/header.php';

// Check if category_id is provided
if(!isset($_GET['category_id']) || empty($_GET['category_id'])){
    echo "<p>Invalid category. Please go back and select a category.</p>";
    include 'includes/footer.php';
    exit;
}

$category_id = intval($_GET['category_id']);

// Fetch category name
$stmt = $pdo->prepare("SELECT * FROM categories WHERE id = ?");
$stmt->execute([$category_id]);
$category = $stmt->fetch(PDO::FETCH_ASSOC);

if(!$category){
    echo "<p>Category not found.</p>";
    include 'includes/footer.php';
    exit;
}

// Fetch all questions for this category
$stmt = $pdo->prepare("SELECT * FROM questions WHERE category_id = ?");
$stmt->execute([$category_id]);
$questions = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch all answers for questions
$answers_stmt = $pdo->prepare("SELECT * FROM answers WHERE question_id = ?");
?>

<h2><?php echo htmlspecialchars($category['name']); ?> Quiz</h2>

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
