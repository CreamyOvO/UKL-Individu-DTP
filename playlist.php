<?php 
session_start();
$sudahLogin = $_SESSION['id'] ?? '';
$role = $_SESSION['role'] ?? '';
require_once 'koneksi.php';

if($role === 'user') {
    header("Location: index.php");
    exit;
}

if($role === '') {
    header("Location: login.php");
    exit;
}

$stmt = $pdo->prepare("SELECT p.id, p.name, p.created_at from user_playlist as up join playlist as p on up.playlist_id = p.id  join user as u on up.user_id = u.id WHERE u.id = :id");
$stmt->execute(['id' => $sudahLogin]);
$total = $stmt->rowCount();
$playlist = $stmt->fetchAll(PDO::FETCH_ASSOC);

if(isset($_POST['delete'])) {
    $playlist_id = $_POST['id'];
    $stmtDelete = $pdo->prepare("DELETE FROM playlist WHERE id = :id");
    $stmtDelete->execute(['id' => $playlist_id]);
    header("Location: playlist.php");
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
                    <h1>List Playlist</h1>
                    <p>There's <?= $total ?> Playlist</p>
                </div>
                <a href="tambah-playlist.php"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-music-note-list" viewBox="0 0 16 16">
                    <path d="M12 13c0 1.105-1.12 2-2.5 2S7 14.105 7 13s1.12-2 2.5-2 2.5.895 2.5 2"/>
                    <path fill-rule="evenodd" d="M12 3v10h-1V3z"/>
                    <path d="M11 2.82a1 1 0 0 1 .804-.98l3-.6A1 1 0 0 1 16 2.22V4l-5 1z"/>
                    <path fill-rule="evenodd" d="M0 11.5a.5.5 0 0 1 .5-.5H4a.5.5 0 0 1 0 1H.5a.5.5 0 0 1-.5-.5m0-4A.5.5 0 0 1 .5 7H8a.5.5 0 0 1 0 1H.5a.5.5 0 0 1-.5-.5m0-4A.5.5 0 0 1 .5 3H8a.5.5 0 0 1 0 1H.5a.5.5 0 0 1-.5-.5"/>
                    </svg>Create Playlist</a>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Playlist Name</th>
                        <th>Created</th>
                        <th>Edit</th>
                        <th>Delete</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($playlist as $i => $p): ?>
                        <tr>
                            <td><?= $i + 1 ?></td>
                            <td><?= $p['name'] ?></td>
                            <td><?= $p['created_at'] ?></td>
                            <td>
                                <form action="edit-playlist.php" method="post">
                                    <input type="hidden" name="playlist_id" value="<?= $p['id'] ?>">
                                    <input type="submit" value="Edit" id="edit">
                                </form>
                            </td>
                            <td>
                                <form action="playlist.php" method="post" onclick="return confirm('Yakin mau Hapus')">
                                    <input type="hidden" value="<?= $p['id'] ?>" name="id">
                                    <input type="submit" value="Delete" id="delete" name="delete">
                                </form>
                            </td>
                        </tr>
                    <?php endforeach;?>
                </tbody>
            </table>
        </section>
    </main>
</body>
</html>