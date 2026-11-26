<?php 
session_start();
require_once 'koneksi.php';
$id = $_SESSION['id'] ?? '';

if($id) {
    header('Location: index.php');
    exit;
}

if(isset($_POST['submit'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $hash = password_hash($password, PASSWORD_DEFAULT);

    $cek = $pdo->prepare("SELECT id FROM user WHERE username = :username");
    $cek->execute(['username' => $username]);
    if($cek->fetch()) {
        header("Location: register.php?error");
    }

    $stmt = $pdo->prepare("INSERT INTO user(username, password) VALUES(:username, :password)");
    $stmt->execute(['username' => $username, 'password' => $hash]);
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="register.css">
</head>
<body>
    <form action="register.php" method="post">
        <h1>Register Account</h1>
        <input type="text" name="username" id="username" placeholder="Username" required>
        <input type="password" name="password" id="password" placeholder="Password" required>
        <div class="checkbox">
            <input type="checkbox" required>
            <label for="checkbox">I Agree with all Terms</label>
        </div>
        <div class="submit">
            <input type="submit" value="Create Account" name="submit" id="submit">
            <a href="login.php">Already Have an Account?</a>
        </div>
    </form>
</body>
</html>