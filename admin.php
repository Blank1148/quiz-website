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

// Function to generate unique quiz code
function generateQuizCode($pdo, $length = 8) {
    $chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    do {
        $code = '';
        for($i = 0; $i < $length; $i++) {
            $code .= $chars[rand(0, strlen($chars) - 1)];
        }
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM categories WHERE quiz_code = ?");
        $stmt->execute([$code]);
        $count = $stmt->fetchColumn();
    } while ($count > 0);
    return $code;
}

// Handle adding a new category
if(isset($_POST['add_category'])){
    $cat_name = trim($_POST['category_name']);
    if(!empty($cat_name)){
        $creator_id = $_SESSION['user_id'];
        $quiz_code = generateQuizCode($pdo);
        $stmt = $pdo->prepare("INSERT INTO categories (name, creator_id, quiz_code) VALUES (?, ?, ?)");
        $stmt->execute([$cat_name, $creator_id, $quiz_code]);
        echo "<p style='color:green;'>Category added successfully! Quiz Code: $quiz_code</p>";
    }
}

// Handle deleting a category
if(isset($_GET['delete_category'])){
    $cat_id = intval($_GET['delete_category']);
    $stmt = $pdo->prepare("SELECT creator_id FROM categories WHERE id = ?");
    $stmt->execute([$cat_id]);
    $category = $stmt->fetch(PDO::FETCH_ASSOC);
    if($category && $category['creator_id'] == $_SESSION['user_id']){
        $stmt = $pdo->prepare("DELETE FROM categories WHERE id = ?");
        $stmt->execute([$cat_id]);
        echo "<p style='color:red;'>Category deleted successfully!</p>";
    } else {
        echo "<p style='color:red;'>You do not have permission to delete this category.</p>";
    }
}

// Fetch categories created by this admin
$stmt = $pdo->prepare("SELECT * FROM categories WHERE creator_id = ? ORDER BY name");
$stmt->execute([$_SESSION['user_id']]);
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h2>Admin Dashboard</h2>
<p><a href="logout.php">Logout</a></p>

<h3>Add New Category</h3>
<form method="post" action="">
    <input type="text" name="category_name" placeholder="Category Name" required>
    <input type="submit" name="add_category" value="Add Category">
</form>

<h3>Your Categories</h3>
<table border="1" cellpadding="5" cellspacing="0">
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Quiz Code</th>
        <th>Actions</th>
    </tr>
    <?php foreach($categories as $category): ?>
    <tr>
        <td><?php echo $category['id']; ?></td>
        <td><?php echo htmlspecialchars($category['name']); ?></td>
        <td><?php echo htmlspecialchars($category['quiz_code']); ?></td>
        <td>
            <a href="manage_questions.php?category_id=<?php echo $category['id']; ?>">Manage Questions</a> |
            <a href="?delete_category=<?php echo $category['id']; ?>" onclick="return confirm('Delete this category?')">Delete</a>
        </td>
    </tr>
    <?php endforeach; ?>
</table>

<?php include 'includes/footer.php'; ?>