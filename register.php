<?php
session_start();
include 'includes/db.php';
include 'includes/header.php';

// Redirect if already logged in
if(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true){
    if($_SESSION['role'] === 'admin'){
        header("Location: admin.php");
    } else {
        header("Location: user_dashboard.php");
    }
    exit;
}

$error = '';
$success = '';

if(isset($_POST['register'])){
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $role = $_POST['role'];

    if(!empty($username) && !empty($password) && in_array($role, ['user', 'admin'])){
        // Check if username exists
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        if($stmt->fetch()){
            $error = "Username already exists.";
        } else {
            $hashed = password_hash($password, PASSWORD_BCRYPT);
            $stmt = $pdo->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
            $stmt->execute([$username, $hashed, $role]);
            $success = "Registration successful! You can now <a href='login.php'>login</a>.";
        }
    } else {
        $error = "Please fill all fields correctly.";
    }
}
?>

<h2>Register</h2>

<?php if($error): ?>
<p style="color:red;"><?php echo $error; ?></p>
<?php endif; ?>

<?php if($success): ?>
<p style="color:green;"><?php echo $success; ?></p>
<?php endif; ?>

<form method="post" action="">
    <p>
        <label>Username:</label><br>
        <input type="text" name="username" required>
    </p>
    <p>
        <label>Password:</label><br>
        <input type="password" name="password" required>
    </p>
    <p>
        <label>Role:</label><br>
        <select name="role">
            <option value="user">User</option>
            <option value="admin">Admin</option>
        </select>
    </p>
    <input type="submit" name="register" value="Register">
</form>

<p>Already have an account? <a href="login.php">Login here</a>.</p>

<?php include 'includes/footer.php'; ?>