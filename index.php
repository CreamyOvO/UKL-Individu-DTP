<?php 
require_once 'koneksi.php';

$total = 0;
$playlistName = [];

if(isset($_GET['playlist_id'])) {
    $playlist_id = $_GET['playlist_id'];
    $playlistMusikRaw = $pdo->prepare("SELECT mp.music_id, mp.playlist_id, m.name, g.name as genre_name, m.duration, m.url, mp.status, mp.added_at from music_playlist as mp join music as m on mp.music_id = m.id join genre as g on m.genre_id = g.id where mp.playlist_id = :playlist_id");
    $playlistNameRaw = $pdo->prepare("SELECT * FROM playlist WHERE id = :playlist_id");
    $playlistNameRaw->execute(['playlist_id' => $playlist_id]);
    $playlistName = $playlistNameRaw->fetch(PDO::FETCH_ASSOC);
    $playlistMusikRaw->execute(['playlist_id' => $playlist_id]);
    $playlistMusic = $playlistMusikRaw->fetchAll(PDO::FETCH_ASSOC);
    $total = $playlistMusikRaw->rowCount();
}

if(isset($_POST['delete'])) {
    $music_id = $_POST['music_id'];
    $stmtDelete = $pdo->prepare("DELETE FROM music WHERE id = :music_id");
    $stmtDelete->execute(['music_id' => $music_id]);
    header("Location: index.php");
    exit;
}

if(isset($_POST['edit'])) {
    $music_id = $_POST['music_id'];
    $playlist_id = $_POST['playlist_id'];
    $status = $_POST['status'];
    $stmtEdit = $pdo->prepare("UPDATE music_playlist SET status = :status WHERE music_id = :music_id AND playlist_id = :playlist_id");
    $stmtEdit->execute(['status' => $status, 'music_id' => $music_id, 'playlist_id' => $playlist_id]);
}

$playlistRaw = $pdo->query("SELECT * FROM playlist");
$playlist = $playlistRaw->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Playlist Music</title>
    <link rel="stylesheet" href="style.css">
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
        <section class="hero">
            <header>
                <h1>Welcome to CreamyMusic!</h1>
                <p>See, Add, Delete, and Edit any music or playlist you want in here!. Try it Now! :D</p>
                <button><a href="#">See</a></button>
            </header>
            <img src="Presentasi Teknologi 3D Futuristik Ungu Putih.png" alt="">
        </section>
        <section class="playlist"> 
            <div class="select">
                <p id="select">Select the Playlist :</p>
                <form action="index.php" method="get">
                    <select name="playlist_id" id="playlist_id" onchange="this.form.submit()">
                        <option value="" disabled selected>--SELECT--</option>
                        <?php if (!empty($playlist)): ?>
                            <?php foreach($playlist as $p): ?>
                                <option value="<?= $p['id'] ?>"><?= $p['name'] ?></option>
                            <?php endforeach; ?>
                            <?php else: ?>
                                <option value="" disabled selected>Kosong</option>
                        <?php endif; ?>
                    </select>
                </form>
                <a href="tambah-playlist.php">Add new Playlist</a>
            </div>
            <?php if(isset($_GET['playlist_id'])): ?>
                <h4><?= $playlistName['name'] ?> Playlist</h4>
                <p id="info">There's <?= $total ?> Songs in this playlist</p>
            <?php endif; ?>
            <?php if(!empty($playlistMusic)): ?>
                <table>
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Song Name</th>
                            <th>Genre</th>
                            <th>Duration</th>
                            <th>Link</th>
                            <th>Status</th>
                            <th>Added At</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($playlistMusic as $i => $song): ?>
                        <tr>
                            <td><?= $i + 1 ?></td>
                            <td><?= $song['name'] ?></td>
                            <td><?= $song['genre_name'] ?></td>
                            <td><?= $song['duration'] ?></td>
                            <td><a href="<?= $song['url'] ?>"></a>Click</td>
                            <td><?= $song['status'] ?></td>
                            <td><?= $song['added_at'] ?></td>
                            <td>
                                    <div class="action">
                                        <form action="index.php" method="post">
                                            <input type="submit" id="delete" value="Delete" name="delete">
                                            <input type="hidden" value="<?= $song['music_id'] ?>" name="music_id">
                                        </form>
                                        <br>
                                        <form action="index.php" method="post">
                                        <select name="status" id="status">
                                            <option value="unhear">Unhear</option>
                                            <option value="heard">Heard</option>
                                        </select>
                                        <input type="hidden" value="<?= $song['music_id'] ?>" name="music_id">
                                        <input type="hidden" value="<?= $song['playlist_id'] ?>" name="playlist_id">
                                        <br>
                                        <input type="submit" value="Edit" name="edit" id="edit">
                                    </form>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>Playlist Kosong</p>
            <?php endif; ?>
        </section>
    </main>
    <footer>
        <p>©2026 - CreamyMusic by CreamyWho</p>
    </footer>
</body>
</html>