<?php 
require_once 'koneksi.php';

if(isset($_POST['create'])) {
    $nama = $_POST['playlistname'];

    $stmt = $pdo->prepare("INSERT INTO playlist(name) VALUES (:name)");
    $stmt->execute(['name' => $nama]);
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
            <a href="index.php">Beranda</a>
            <a href="playlist.php">Playlist</a>
            <a href="tambah-lagu.php">Tambah</a>
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