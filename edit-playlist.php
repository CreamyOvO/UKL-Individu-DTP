<?php 
require_once 'koneksi.php';

$playlist_id = $_POST['playlist_id'];

if(isset($_POST['edit'])) {
    $nama = $_POST['playlistname'];
    $id = $_POST['id'];
    $stmt = $pdo->prepare("UPDATE playlist SET name = :name WHERE id = :playlist_id");
    $stmt->execute(['name' => $nama, 'playlist_id' => $id]);
    header("Location: playlist.php");
    exit;
}

$stmtSelect = $pdo->prepare("SELECT * FROM playlist WHERE id = :id");
$stmtSelect->execute(['id' => $playlist_id]);
$playlist = $stmtSelect->fetch(PDO::FETCH_ASSOC);
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
            <h1>Edit Playlist</h1>
            <form action="edit-playlist.php" method="post">
                <label for="playlistname">Playlist Name:</label>
                <input type="text" required name="playlistname" id="playlistname" value="<?= $playlist['name'] ?>">
                <input type="hidden" name="id" value="<?= $playlist['id'] ?>">
                <input type="submit" value="Edit" name="edit" id="add">
            </form>
        </section>
    </main>
</body>
</html>