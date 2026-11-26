<?php 
session_start();
$sudahLogin = $_SESSION['id'] ?? '';
$role = $_SESSION['role'] ?? '';
require_once 'koneksi.php';




$duration = 0;

$stmt = $pdo->query("SELECT m.id as music_id, m.name as music_name, g.name as genre_name, m.duration, m.url, m.created_at FROM music as m JOIN genre as g on m.genre_id = g.id");
$total = $stmt->rowCount();
$music = $stmt->fetchAll(PDO::FETCH_ASSOC);
$stmtUser = $pdo->prepare("SELECT * FROM user WHERE id = :id");
$stmtUser->execute(['id' => $sudahLogin]);
$user = $stmtUser->fetch(PDO::FETCH_ASSOC);

if(isset($_POST['delete'])) {
    $music_id = $_POST['music_id'];
    $stmtDelete = $pdo->prepare("DELETE FROM music WHERE id = :id");
    $stmtDelete->execute(['id' => $music_id]);
    header("Location: music.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Playlist</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="topbar">
        <a href="index.php"><h2>CreamyMusic.</h2></a>
        <nav>
            <a href="index.php"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-house-door-fill" viewBox="0 0 16 16">
            <path d="M6.5 14.5v-3.505c0-.245.25-.495.5-.495h2c.25 0 .5.25.5.5v3.5a.5.5 0 0 0 .5.5h4a.5.5 0 0 0 .5-.5v-7a.5.5 0 0 0-.146-.354L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293L8.354 1.146a.5.5 0 0 0-.708 0l-6 6A.5.5 0 0 0 1.5 7.5v7a.5.5 0 0 0 .5.5h4a.5.5 0 0 0 .5-.5"/>
            </svg>Home</a>
            <a href="playlist.php"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-music-note-list" viewBox="0 0 16 16">
            <path d="M12 13c0 1.105-1.12 2-2.5 2S7 14.105 7 13s1.12-2 2.5-2 2.5.895 2.5 2"/>
            <path fill-rule="evenodd" d="M12 3v10h-1V3z"/>
            <path d="M11 2.82a1 1 0 0 1 .804-.98l3-.6A1 1 0 0 1 16 2.22V4l-5 1z"/>
            <path fill-rule="evenodd" d="M0 11.5a.5.5 0 0 1 .5-.5H4a.5.5 0 0 1 0 1H.5a.5.5 0 0 1-.5-.5m0-4A.5.5 0 0 1 .5 7H8a.5.5 0 0 1 0 1H.5a.5.5 0 0 1-.5-.5m0-4A.5.5 0 0 1 .5 3H8a.5.5 0 0 1 0 1H.5a.5.5 0 0 1-.5-.5"/>
            </svg>Playlist</a>
            <a href="music.php" class="selected"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-music-note" viewBox="0 0 16 16">
            <path d="M9 13c0 1.105-1.12 2-2.5 2S4 14.105 4 13s1.12-2 2.5-2 2.5.895 2.5 2"/>
            <path fill-rule="evenodd" d="M9 3v10H8V3z"/>
            <path d="M8 2.82a1 1 0 0 1 .804-.98l3-.6A1 1 0 0 1 13 2.22V4L8 5z"/>
            </svg>Music</a>
        </nav>
        <div class="profile">
            <?php if($sudahLogin): ?>
                <div id="profile"></div>
                <form action="logout.php" method="post" onclick="return confirm('Yakin mau Logout?')"> 
                    <input type="submit" name="logout" value="Logout" id="logout">
                </form>
            <?php else: ?>
                <a href="register.php">Register</a>
            <?php endif; ?>
        </div>
    </div>
    <main>
        <section class="playlist">
            <div class="select">
                <div class="info">
                    <h1>List Music</h1>
                    <p>There's <?= $total ?> Music in Database</p>
                </div>
                <?php if($user['role'] === 'admin'): ?>
                <a href="tambah-lagu.php"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-music-note-list" viewBox="0 0 16 16">
                    <path d="M12 13c0 1.105-1.12 2-2.5 2S7 14.105 7 13s1.12-2 2.5-2 2.5.895 2.5 2"/>
                    <path fill-rule="evenodd" d="M12 3v10h-1V3z"/>
                    <path d="M11 2.82a1 1 0 0 1 .804-.98l3-.6A1 1 0 0 1 16 2.22V4l-5 1z"/>
                    <path fill-rule="evenodd" d="M0 11.5a.5.5 0 0 1 .5-.5H4a.5.5 0 0 1 0 1H.5a.5.5 0 0 1-.5-.5m0-4A.5.5 0 0 1 .5 7H8a.5.5 0 0 1 0 1H.5a.5.5 0 0 1-.5-.5m0-4A.5.5 0 0 1 .5 3H8a.5.5 0 0 1 0 1H.5a.5.5 0 0 1-.5-.5"/>
                    </svg>Add Music</a>
                <?php endif; ?>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Song Name</th>
                        <th>Genre</th>
                        <th>Duration</th>
                        <th>Link</th>
                        <th>Created</th>
                        <th>Add to Playlist</th>
                        <?php if($role === 'admin'): ?>
                            <th>Edit</th>
                            <th>Delete</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($music as $i => $m): ?>
                        <?php   $minutes = intdiv($m['duration'], 60);
                                $seconds = $m['duration'] % 60;
                                $duration = sprintf('%d:%02d Minutes', $minutes, $seconds);
                        ?>
                        <tr>
                            <td><?= $i + 1 ?></td>
                            <td><?= $m['music_name'] ?></td>
                            <td><?= $m['genre_name'] ?></td>
                            <td><?= $duration ?></td>
                            <td><?= $m['url'] ?></td>
                            <td><?= $m['created_at'] ?></td>
                            <td>
                                <form action="music-add-playlist.php" method="post">
                                    <input type="hidden" value="<?= $m['music_id'] ?>" name="music_id">
                                    <input type="hidden" value="<?= $m['music_name'] ?>" name="music_name">
                                    <input type="submit" id="add" value="Add+">
                                </form>
                            </td>
                            <?php if($role === 'admin'): ?>
                                <td>
                                    <form action="edit-lagu.php" method="post">
                                        <input type="hidden" name="music_id" value="<?= $m['music_id'] ?>">
                                        <input type="submit" value="Edit" id="edit" name="edit_old">
                                    </form>
                                </td>
                                <td>
                                    <form action="music.php" method="post" onclick="return confirm('Yakin mau hapus?')">
                                        <input type="hidden" value="<?= $m['music_id'] ?>" name="id">
                                        <input type="submit" value="Delete" id="delete" name="delete">
                                    </form>
                                </td>
                            <?php endif; ?>
                        </tr>
                    <?php endforeach;?>
                </tbody>
            </table>
        </section>
    </main>
</body>
</html>