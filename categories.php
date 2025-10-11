<?php
include 'includes/db.php';
include 'includes/header.php';

// Fetch all categories from the database
$stmt = $pdo->query("SELECT * FROM categories ORDER BY name");
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h2>Quiz Categories</h2>
<p>Select a category to start the quiz:</p>

<div class="categories">
    <?php if(count($categories) > 0): ?>
        <?php foreach($categories as $category): ?>
            <div class="category-card">
                <h3><?php echo htmlspecialchars($category['name']); ?></h3>
                <a href="quiz.php?category_id=<?php echo $category['id']; ?>">Start Quiz</a>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No quiz categories available at the moment.</p>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>
