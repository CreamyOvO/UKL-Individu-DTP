<?php 
session_start();
$sudahLogin = $_SESSION['id'] ?? '';
require_once 'koneksi.php';

$total = 0;
$playlistName = [];
$duration = 0;

if(isset($_GET['playlist_id'])) {
    $playlist_id = $_GET['playlist_id'];
    $playlistMusikRaw = $pdo->prepare("SELECT mp.id, m.id as music_id, p.id as playlist_id, m.name as music_name, g.name as genre_name, m.duration, m.url, mp.status, mp.added_at from music_playlist as mp INNER JOIN music as m on mp.music_id = m.id INNER JOIN playlist as p on mp.playlist_id = p.id INNER JOIN genre as g ON m.genre_id = g.id where mp.playlist_id = :playlist_id ORDER BY mp.added_at ASC");
    $playlistNameRaw = $pdo->prepare("SELECT * FROM playlist WHERE id = :playlist_id");
    $playlistNameRaw->execute(['playlist_id' => $playlist_id]);
    $playlistName = $playlistNameRaw->fetch(PDO::FETCH_ASSOC);
    $playlistMusikRaw->execute(['playlist_id' => $playlist_id]);
    $playlistMusic = $playlistMusikRaw->fetchAll(PDO::FETCH_ASSOC);
    $total = $playlistMusikRaw->rowCount();
}

if(isset($_POST['delete'])) {
    $id = $_POST['id'];
    $stmtDelete = $pdo->prepare("DELETE FROM music_playlist WHERE id = :id");
    $stmtDelete->execute(['id' => $id]);
    header("Location: index.php");
    exit;
}

if(isset($_POST['edit'])) {
    $id = $_POST['id'];
    $status = $_POST['status'];
    $stmtEdit = $pdo->prepare("UPDATE music_playlist SET status = :status WHERE id = :id");
    $stmtEdit->execute(['status' => $status, 'id' => $id]);
}

$playlistRaw = $pdo->prepare("SELECT p.id, p.name FROM user_playlist as up INNER JOIN playlist as p ON up.playlist_id = p.id INNER JOIN user as u ON up.user_id = u.id WHERE u.id = :id");
$playlistRaw->execute(['id' => $sudahLogin]);
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
            <a href="index.php" class="selected"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-house-door-fill" viewBox="0 0 16 16">
            <path d="M6.5 14.5v-3.505c0-.245.25-.495.5-.495h2c.25 0 .5.25.5.5v3.5a.5.5 0 0 0 .5.5h4a.5.5 0 0 0 .5-.5v-7a.5.5 0 0 0-.146-.354L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293L8.354 1.146a.5.5 0 0 0-.708 0l-6 6A.5.5 0 0 0 1.5 7.5v7a.5.5 0 0 0 .5.5h4a.5.5 0 0 0 .5-.5"/>
            </svg>Home</a>
            <a href="playlist.php"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-music-note-list" viewBox="0 0 16 16">
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
    </div>
    <main>
        <section class="hero">
            <header>
                <h1>Welcome to <span>CreamyMusic!<span></h1>
                <p>See, Add, Delete, and Edit any music or playlist you want in here!. Try it Now! :D</p>
            </header>
            <img src="Presentasi Teknologi 3D Futuristik Ungu Putih.png" alt="">
        </section>
        <section class="playlist"> 
            <div class="select">
                <div class="selection">
                    <img src="music-note-beamed.svg" alt="" width="48px">
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
                </div>
                <a href="tambah-playlist.php"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-music-note-list" viewBox="0 0 16 16">
                    <path d="M12 13c0 1.105-1.12 2-2.5 2S7 14.105 7 13s1.12-2 2.5-2 2.5.895 2.5 2"/>
                    <path fill-rule="evenodd" d="M12 3v10h-1V3z"/>
                    <path d="M11 2.82a1 1 0 0 1 .804-.98l3-.6A1 1 0 0 1 16 2.22V4l-5 1z"/>
                    <path fill-rule="evenodd" d="M0 11.5a.5.5 0 0 1 .5-.5H4a.5.5 0 0 1 0 1H.5a.5.5 0 0 1-.5-.5m0-4A.5.5 0 0 1 .5 7H8a.5.5 0 0 1 0 1H.5a.5.5 0 0 1-.5-.5m0-4A.5.5 0 0 1 .5 3H8a.5.5 0 0 1 0 1H.5a.5.5 0 0 1-.5-.5"/>
                    </svg>Create Playlist</a>
            </div>
            <?php if(isset($_GET['playlist_id'])): ?>
                <h4><?= $playlistName['name'] ?> Playlist</h4>
                <p id="info">There's <?= $total ?> Songs in this playlist</p>
                <a href="music.php" id="add_music">Add Music</a>
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
                            <th>Edit</th>
                            <th>Delete</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($playlistMusic as $i => $song): ?>
                            <?php 
                                $minutes = intdiv($song['duration'], 60);
                                $seconds = $song['duration'] % 60;
                                $duration = sprintf('%d:%02d Minutes', $minutes, $seconds);
                            ?>
                        <tr>
                            <td><?= $i + 1 ?></td>
                            <td><?= $song['music_name'] ?></td>
                            <td><?= $song['genre_name'] ?></td>
                            <td><?= $duration ?></td>
                            <td><a href="<?= $song['url'] ?>">Click</a></td>
                            <td>
                                <?php if($song['status'] === 'unhear'): ?>
                                    <p class="unhear">Unhear</p>
                                <?php else: ?>
                                    <p class="heard">Heard</p>
                                <?php endif; ?>
                            </td>
                            <td><?= $song['added_at'] ?></td>
                            <td>
                                <br>
                                <form action="index.php" method="post">
                                    <select name="status" id="status">
                                        <option value="unhear">Unhear</option>
                                        <option value="heard">Heard</option>
                                    </select>
                                    <input type="hidden" value="<?= $song['id'] ?>" name="id">
                                    <br>
                                    <input type="submit" value="Edit" name="edit" id="edit">
                                </form>
                            </td>
                            <td>
                                <form action="index.php" method="post">
                                    <input type="submit" id="delete" value="Delete" name="delete">
                                    <input type="hidden" value="<?= $song['id'] ?>" name="id">
                                </form>
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