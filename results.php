<?php
include 'includes/db.php';
include 'includes/header.php';

// Check if form is submitted
if(!isset($_POST['answers']) || !isset($_POST['category_id'])){
    echo "<p>No quiz data submitted.</p>";
    include 'includes/footer.php';
    exit;
}

$category_id = intval($_POST['category_id']);
$user_answers = $_POST['answers'];

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

$total_questions = count($questions);
$correct_count = 0;

echo "<h2>Results for: " . htmlspecialchars($category['name']) . " Quiz</h2>";

foreach($questions as $index => $question){
    $qid = $question['id'];
    $correct_answer = $question['correct_answer'];
    $user_answer = isset($user_answers[$qid]) ? $user_answers[$qid] : '';

    echo "<div class='question-block'>";
    echo "<p><strong>Q" . ($index+1) . ": " . htmlspecialchars($question['question_text']) . "</strong></p>";
    echo "<p>Your answer: " . htmlspecialchars($user_answer) . "</p>";
    echo "<p>Correct answer: " . htmlspecialchars($correct_answer) . "</p>";

    if($user_answer === $correct_answer){
        echo "<p style='color:green;'>Correct!</p>";
        $correct_count++;
    } else {
        echo "<p style='color:red;'>Incorrect.</p>";
    }
    echo "</div><hr>";
}

// Display total score
echo "<h3>Total Score: $correct_count / $total_questions</h3>";

// Optional: Save result to the database
/*
$stmt = $pdo->prepare("INSERT INTO results (user_name, category_id, score) VALUES (?, ?, ?)");
$user_name = "Guest"; // You can replace with actual user login if implemented
$stmt->execute([$user_name, $category_id, $correct_count]);
*/
?>

<a href="index.php">Back to Home</a>

<?php include 'includes/footer.php'; ?>
