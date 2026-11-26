<?php 
session_start();
$sudahLogin = $_SESSION['id'] ?? '';
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
            <a href="index.php"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-house-door-fill" viewBox="0 0 16 16">
            <path d="M6.5 14.5v-3.505c0-.245.25-.495.5-.495h2c.25 0 .5.25.5.5v3.5a.5.5 0 0 0 .5.5h4a.5.5 0 0 0 .5-.5v-7a.5.5 0 0 0-.146-.354L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293L8.354 1.146a.5.5 0 0 0-.708 0l-6 6A.5.5 0 0 0 1.5 7.5v7a.5.5 0 0 0 .5.5h4a.5.5 0 0 0 .5-.5"/>
            </svg>Home</a>
            <a href="playlist.php" class="selected"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-music-note-list" viewBox="0 0 16 16">
            <path d="M12 13c0 1.105-1.12 2-2.5 2S7 14.105 7 13s1.12-2 2.5-2 2.5.895 2.5 2"/>
            <path fill-rule="evenodd" d="M12 3v10h-1V3z"/>
            <path d="M11 2.82a1 1 0 0 1 .804-.98l3-.6A1 1 0 0 1 16 2.22V4l-5 1z"/>
            <path fill-rule="evenodd" d="M0 11.5a.5.5 0 0 1 .5-.5H4a.5.5 0 0 1 0 1H.5a.5.5 0 0 1-.5-.5m0-4A.5.5 0 0 1 .5 7H8a.5.5 0 0 1 0 1H.5a.5.5 0 0 1-.5-.5m0-4A.5.5 0 0 1 .5 3H8a.5.5 0 0 1 0 1H.5a.5.5 0 0 1-.5-.5"/>
            </svg>Playlist</a>
            <a href="music.php"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-music-note" viewBox="0 0 16 16">
            <path d="M9 13c0 1.105-1.12 2-2.5 2S4 14.105 4 13s1.12-2 2.5-2 2.5.895 2.5 2"/>
            <path fill-rule="evenodd" d="M9 3v10H8V3z"/>
            <path d="M8 2.82a1 1 0 0 1 .804-.98l3-.6A1 1 0 0 1 13 2.22V4L8 5z"/>
            </svg>Music</a>
        </nav>
        <div class="profile">
            <?php if($sudahLogin): ?>
                <div id="profile"></div>
                <form action="logout.php" method="post">
                    <input type="submit" name="logout" value="Logout" id="logout">
                </form>
            <?php else: ?>
                <a href="register.php">Register</a>
            <?php endif; ?>
        </div>
        <div class="profile">
            <span></span>
        </div>
    </div>
    <main>
        <section class="playlist">
            <h1>Edit Playlist</h1>
            <div class="form">
                <form action="edit-playlist.php" method="post">
                    <label for="playlistname">Playlist Name:</label>
                    <input type="text" required name="playlistname" id="playlistname" value="<?= $playlist['name'] ?>">
                    <input type="hidden" name="id" value="<?= $playlist['id'] ?>">
                    <input type="submit" value="Edit" name="edit" id="add">
                </form>
            </div>
        </section>
    </main>
</body>
</html>