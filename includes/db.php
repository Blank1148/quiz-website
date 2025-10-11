<?php
// db.php
$host = "localhost";
$dbname = "quiz_website";  // database created earlier
$user = "root";            // default XAMPP MySQL user
$pass = "";                // default XAMPP MySQL password is empty

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>
