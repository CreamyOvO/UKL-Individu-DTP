<?php 
require_once 'koneksi.php';

if(isset($_POST['add'])) {
    $nama = $_POST['songname'];
    $genre = $_POST['genre'];
    $duration = $_POST['duration'];
    $link = $_POST['link'];
    $playlist_id = $_POST['playlist'];
    
    $pdo->beginTransaction();
    $stmt = $pdo->prepare("INSERT INTO music(name, genre_id, duration, url) values (:name, :genre_id, :duration, :link)");
    $stmt->execute(['name' => $nama, 'genre_id' => $genre, 'duration' => $duration, 'link' => $link]);

    $music_id = $pdo->lastInsertId();
    $stmtPlaylistId = $pdo->prepare("INSERT INTO music_playlist(music_id, playlist_id) VALUES (:music_id, :playlist_id)");
    $stmtPlaylistId->execute(['music_id' => $music_id, 'playlist_id' => $playlist_id]);
    $pdo->commit();
    header("Location: index.php");
    exit;

}

$stmtGenre = $pdo->query("SELECT * FROM genre");
$genre = $stmtGenre->fetchAll(PDO::FETCH_ASSOC);

$stmtPlaylist = $pdo->query("SELECT * FROM playlist");
$playlist = $stmtPlaylist->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Lagu</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="tambah-lagu.css">
</head>
<body>
    <div class="topbar">
        <h2>CreamyMusic.</h2>
        <nav>
            <a href="index.php">Beranda</a>
            <a href="playlist.php">Playlist</a>
            <a href="#">Tambah</a>
        </nav>
    </div>
    <main>
        <section class="add">
            <h1>Add New Song</h1>
            <form action="tambah-lagu.php" method="post">
                <label for="songname">Song Name:</label>
                <input type="text" id="songname" name="songname" required>
                <label for="genre">Select Genre:</label>
                <select name="genre" id="genre" required>
                    <option value="#" disabled selected>--SELECT--</option>
                    <?php foreach($genre as $g):?>
                        <option value="<?= $g['id'] ?>"><?= $g['name'] ?></option>
                    <?php endforeach; ?>
                </select>
                <label for="duration">Duratio(in Seconds):</label>
                <input type="number" id="duration" name="duration" required>
                <label for="link">Song Link:</label>
                <input type="text" id="link" name="link" required>
                <label for="playlist">Playlist:</label>
                <select name="playlist" id="playlist" required>
                    <option value="#" selected disabled>--SELECT--</option>
                    <?php foreach($playlist as $p):?>
                        <option value="<?= $p['id'] ?>"><?= $p['name'] ?></option>
                    <?php endforeach; ?>
                </select>
                <input type="submit" name="add" value="Add" id="add"> 
            </form>
        </section>
    </main>
</body>
</html>