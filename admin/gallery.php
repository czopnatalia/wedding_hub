<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit;
}
require '../includes/db.php';

if (isset($_GET['approve'])) {
    $id = (int)$_GET['approve'];
    $stmt = $conn->prepare("SELECT * FROM photos WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $photo = $stmt->get_result()->fetch_assoc();
    if ($photo) {
        $from = "../uploads/pending/".$photo['filename'];
        $to = "../uploads/approved/".$photo['filename'];
        if (file_exists($from)) {
            rename($from, $to);
        }
        $conn->query("UPDATE photos SET status='approved' WHERE id=$id");
    }
    header("Location: gallery.php");
    exit;
}

if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $stmt = $conn->prepare("SELECT * FROM photos WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $photo = $stmt->get_result()->fetch_assoc();
    if ($photo) {
        $from1 = "../uploads/pending/".$photo['filename'];
        $from2 = "../uploads/approved/".$photo['filename'];
        if (file_exists($from1)) unlink($from1);
        if (file_exists($from2)) unlink($from2);
        $conn->query("DELETE FROM photos WHERE id=$id");
    }
    header("Location: gallery.php");
    exit;
}

$res = $conn->query("SELECT * FROM photos WHERE status='pending' ORDER BY uploaded_at ASC");
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Moderacja galerii</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
<div class="main">
    <h2>Moderacja zdjęć</h2>
    <p><a href="dashboard.php">← Powrót do panelu</a></p>

    <?php while ($row = $res->fetch_assoc()): ?>
        <div style="margin-bottom:20px;">
            <img src="../uploads/pending/<?= htmlspecialchars($row['filename']) ?>" width="200"><br>
            <strong><?= htmlspecialchars($row['uploader_name']) ?></strong><br>
            <a href="?approve=<?= $row['id'] ?>">Akceptuj</a> |
            <a href="?delete=<?= $row['id'] ?>" onclick="return confirm('Usunąć zdjęcie?');">Usuń</a>
        </div>
    <?php endwhile; ?>
</div>
</body>
</html>
