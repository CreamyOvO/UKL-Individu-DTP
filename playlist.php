<?php 
require_once 'koneksi.php';

$stmt = $pdo->query("SELECT * FROM playlist");
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
        <h2>CreamyMusic.</h2>
        <nav>
            <a href="index.php">Beranda</a>
            <a href="playlist.php">Playlist</a>
            <a href="tambah-lagu.php">Tambah</a>
        </nav>
    </div>
    <main>
        <section class="playlist">
            <h1>List Playlist</h1>
            <p>There's <?= $total ?> Playlist</p>
            <table>
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Playlist Name</th>
                        <th>Created</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($playlist as $i => $p): ?>
                        <tr>
                            <td><?= $i + 1 ?></td>
                            <td><?= $p['name'] ?></td>
                            <td><?= $p['created_at'] ?></td>
                            <td>
                                <div class="action">
                                    <form action="playlist.php" method="post">
                                        <input type="hidden" value="<?= $p['id'] ?>" name="id">
                                        <input type="submit" value="Delete" id="delete" name="delete">
                                    </form>
                                    <form action="edit-playlist.php" method="post">
                                        <input type="hidden" name="playlist_id" value="<?= $p['id'] ?>">
                                        <input type="submit" value="Edit" id="edit">
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach;?>
                </tbody>
            </table>
        </section>
    </main>
</body>
</html>