<?php
session_start();
include 'includes/db.php';
include 'includes/header.php';

// Check if logged in
if(!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true){
    header("Location: login.php");
    exit;
}

// Check if form is submitted
if(!isset($_POST['answers']) || !isset($_POST['category_id'])){
    echo "<p>No quiz data submitted.</p>";
    include 'includes/footer.php';
    exit;
}

$category_id = intval($_POST['category_id']);
$user_answers = $_POST['answers'];

// Fetch category
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

?>

<style>
    /* Results Page Specific Styles */
    .question-block {
        background-color: #fff;
        padding: 1.5rem;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        margin-bottom: 1.5rem;
    }

    .question-block p {
        margin-bottom: 0.5rem;
        color: #34495e;
    }

    .question-block p strong {
        font-weight: bold;
    }

    p[style*="color:red"] {
        background-color: #ffe6e6;
        padding: 0.8rem;
        border-radius: 4px;
        text-align: center;
    }

    p[style*="color:green"] {
        background-color: #e6ffed;
        padding: 0.8rem;
        border-radius: 4px;
        text-align: center;
    }

    hr {
        border: 0;
        border-top: 1px solid #ddd;
        margin: 1rem 0;
    }

    h3 {
        color: #34495e;
        margin: 1.5rem 0 1rem;
        font-size: 1.4rem;
        text-align: center;
    }

    .back-link {
        display: inline-block;
        margin-top: 1rem;
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

    p {
        margin-bottom: 1rem;
        font-size: 1rem;
        color: #34495e;
        text-align: center;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .question-block {
            padding: 1rem;
        }
    }
</style>

<h2>Results for: <?php echo htmlspecialchars($category['name']); ?> Quiz (Code: <?php echo htmlspecialchars($category['quiz_code']); ?>)</h2>

<?php
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

// Save result to the database
$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("INSERT INTO results (user_id, category_id, score) VALUES (?, ?, ?)");
$stmt->execute([$user_id, $category_id, $correct_count]);
?>

<a href="user_dashboard.php" class="back-link">Back to Dashboard</a>

<?php include 'includes/footer.php'; ?>