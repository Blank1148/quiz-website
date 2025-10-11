<?php
session_start();
include 'includes/db.php';
include 'includes/header.php';

// Simple session check for admin login
if(!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true){
    echo "<p>You must <a href='login.php'>login</a> to access the admin panel.</p>";
    include 'includes/footer.php';
    exit;
}

// Handle adding a new category
if(isset($_POST['add_category'])){
    $cat_name = trim($_POST['category_name']);
    if(!empty($cat_name)){
        $stmt = $pdo->prepare("INSERT INTO categories (name) VALUES (?)");
        $stmt->execute([$cat_name]);
        echo "<p style='color:green;'>Category added successfully!</p>";
    }
}

// Handle deleting a category
if(isset($_GET['delete_category'])){
    $cat_id = intval($_GET['delete_category']);
    $stmt = $pdo->prepare("DELETE FROM categories WHERE id = ?");
    $stmt->execute([$cat_id]);
    echo "<p style='color:red;'>Category deleted successfully!</p>";
}

// Fetch all categories
$categories = $pdo->query("SELECT * FROM categories ORDER BY name")->fetchAll(PDO::FETCH_ASSOC);
?>

<h2>Admin Dashboard</h2>
<p><a href="logout.php">Logout</a></p>

<h3>Add New Category</h3>
<form method="post" action="">
    <input type="text" name="category_name" placeholder="Category Name" required>
    <input type="submit" name="add_category" value="Add Category">
</form>

<h3>Existing Categories</h3>
<table border="1" cellpadding="5" cellspacing="0">
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Actions</th>
    </tr>
    <?php foreach($categories as $category): ?>
    <tr>
        <td><?php echo $category['id']; ?></td>
        <td><?php echo htmlspecialchars($category['name']); ?></td>
        <td>
            <a href="manage_questions.php?category_id=<?php echo $category['id']; ?>">Manage Questions</a> |
            <a href="?delete_category=<?php echo $category['id']; ?>" onclick="return confirm('Delete this category?')">Delete</a>
        </td>
    </tr>
    <?php endforeach; ?>
</table>

<?php include 'includes/footer.php'; ?>
