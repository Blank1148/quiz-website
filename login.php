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

if(isset($_POST['login'])){
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    if(!empty($username) && !empty($password)){
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if($user && password_verify($password, $user['password'])){
            $_SESSION['logged_in'] = true;
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            if($user['role'] === 'admin'){
                header("Location: admin.php");
            } else {
                header("Location: user_dashboard.php");
            }
            exit;
        } else {
            $error = "Invalid username or password.";
        }
    } else {
        $error = "Please enter username and password.";
    }
}
?>

<h2>Login</h2>

<?php if($error): ?>
<p style="color:red;"><?php echo $error; ?></p>
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
    <input type="submit" name="login" value="Login">
</form>

<p>Don't have an account? <a href="register.php">Register here</a>.</p>

<?php include 'includes/footer.php'; ?>