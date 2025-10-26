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

<style>
    /* Register Page Specific Styles */
    form {
        background-color: #fff;
        padding: 2rem;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        max-width: 600px;
        margin: 0 auto;
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

    form input[type="text"],
    form input[type="password"],
    form select {
        width: 100%;
        padding: 0.8rem;
        border: 1px solid #ccc;
        border-radius: 4px;
        font-size: 1rem;
        transition: border-color 0.3s;
    }

    form input[type="text"]:focus,
    form input[type="password"]:focus,
    form select:focus {
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
    }
</style>

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