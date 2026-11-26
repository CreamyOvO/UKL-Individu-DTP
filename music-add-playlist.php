<?php 
session_start();
$id = $_SESSION['id'] ?? '';
require_once 'koneksi.php';

$stmtPlaylist = $pdo->prepare("SELECT p.id, p.name from user_playlist as up INNER JOIN user as u on up.user_id = u.id INNER JOIN playlist as p on up.playlist_id = p.id WHERE up.user_id = :id");
$stmtPlaylist->execute(['id' => $id]);
$playlist = $stmtPlaylist->fetchAll(PDO::FETCH_ASSOC);
$playlist_id = [];

$music_name = $_POST['music_name'] ?? '';
$music_id = $_POST['music_id'] ?? '';

if(isset($_POST['add'])) {
    $playlist_id = $_POST['playlist'] ?? [];
    foreach($playlist_id as $pid) {
        $stmtPlaylistId = $pdo->prepare("INSERT IGNORE INTO music_playlist(music_id, playlist_id) VALUES (:music_id, :playlist_id)");
        $stmtPlaylistId->execute(['music_id' => $music_id, 'playlist_id' => $pid]);
    }
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah ke Playlist</title>
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
            <h1>Add <?= $music_name ?> to your Playlist</h1>
            <form action="music-add-playlist.php" method="post">
                <label for="playlistname">Playlist Name:</label>
                <div class="checkbox">
                    <?php foreach($playlist as $p):?> 
                        <input type="hidden" name="music_id" value="<?= $music_id ?>">
                        <input type="checkbox" name="playlist[]" value="<?= $p['id'] ?>">
                        <label for="playlist"><?= $p['name'] ?></label>
                    <?php endforeach; ?>
                </div>
                <input type="submit" name="add" value="Add to Playlist" id="add">
            </form>
        </section>
    </main>
</body>
</html>