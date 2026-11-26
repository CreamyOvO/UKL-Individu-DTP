<?php 
require_once 'koneksi.php';
session_start();
$id = $_SESSION['id'] ?? '';

if($id) {
    header('Location: index.php');
    exit;
}

if(isset($_POST['submit'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $cek = $pdo->prepare("SELECT id, username, password FROM user WHERE username = :username");
    $cek->execute(['username' => $username]);
    $user = $cek->fetch(PDO::FETCH_ASSOC);
    if(!$user || !password_verify($password, $user['password'])) {
        header("Location: login.php?error");
        exit;
    }

    $_SESSION['id'] = $user['id'];
    $_SESSION['username'] = $user['username'];

    header("Location: index.php");
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
    <form action="login.php" method="post">
        <h1>Login Account</h1>
        <input type="text" name="username" id="username" placeholder="Username" required>
        <input type="password" name="password" id="password" placeholder="Password" required>
        <div class="checkbox">
            <input type="checkbox" required>
            <label for="checkbox">Remember me</label>
        </div>
        <div class="submit">
            <input type="submit" value="Login" name="submit" id="submit">
            <a href="register.php">Don't have an Account?</a>
        </div>
    </form>
</body>
</html>