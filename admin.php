<?php
session_start();
include 'includes/db.php';
include 'includes/header.php';

// Check if logged in and is admin
if(!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || $_SESSION['role'] !== 'admin'){
    echo "<p style='color:red;'>You must <a href='login.php'>login</a> as admin to access the admin panel.</p>";
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

<style>
    /* Admin Page Specific Styles */
    form {
        background-color: #fff;
        padding: 2rem;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        max-width: 600px;
        margin: 0 auto 2rem;
    }

    form input[type="text"] {
        width: 100%;
        padding: 0.8rem;
        border: 1px solid #ccc;
        border-radius: 4px;
        font-size: 1rem;
        transition: border-color 0.3s;
    }

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
    }
</style>

<h2>Admin Dashboard</h2>

<h3>Add New Category</h3>
<form method="post" action="">
    <input type="text" name="category_name" placeholder="Category Name" required>
    <input type="submit" name="add_category" value="Add Category">
</form>

<h3>Your Categories</h3>
<table>
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