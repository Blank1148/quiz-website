<?php
session_start();
include 'includes/db.php';
include 'includes/header.php';

// Redirect if already logged in
if(isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true){
    header("Location: admin.php");
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
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_username'] = $user['username'];
            header("Location: admin.php");
            exit;
        } else {
            $error = "Invalid username or password.";
        }
    } else {
        $error = "Please enter username and password.";
    }
}
?>

<h2>Admin Login</h2>

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

<?php include 'includes/footer.php'; ?>
