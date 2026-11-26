<?php 
session_start();
$id = $_SESSION['id'] ?? '';
require_once 'koneksi.php';

if(isset($_POST['create'])) {
    $nama = $_POST['playlistname'];
    $pdo->beginTransaction();
    $stmt = $pdo->prepare("INSERT INTO playlist(name) VALUES (:name)");
    $stmt->execute(['name' => $nama]);
    $playlist_id = $pdo->lastInsertId();
    $stmtUser = $pdo->prepare("INSERT INTO user_playlist(user_id, playlist_id) VALUES(:user, :playlist)");
    $stmtUser->execute(['user' => $id, 'playlist' => $playlist_id]);
    $pdo->commit();
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Playlist</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="tambah-lagu.css">
</head>
<body>
    <div class="topbar">
        <h2>CreamyMusic.</h2>
        <nav>
            <a href="index.php">Home</a>
            <a href="playlist.php">Playlist</a>
            <a href="music.php">Music</a>
            <a href="tambah-lagu.php">Add</a>
        </nav>
    </div>
    <main>
        <section class="playlist">
            <h1>Create New Playlist</h1>
            <form action="tambah-playlist.php" method="post">
                <label for="playlistname">Playlist Name:</label>
                <input type="text" required name="playlistname" id="playlistname">
                <input type="submit" value="Create" name="create" id="add">
            </form>
        </section>
    </main>
</body>
</html>