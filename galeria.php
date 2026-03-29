<?php
include 'includes/header.php';
include 'includes/db.php';

$res = $db->query("SELECT * FROM photos WHERE status='approved' ORDER BY uploaded_at DESC");
?>

<h2>Galeria zdjęć</h2>
<p><a href="upload.php">Prześlij swoje zdjęcie</a></p>

<div style="display:flex;flex-wrap:wrap;gap:10px;">
<?php while ($row = $res->fetch_assoc()): ?>
    <div>
        <img src="uploads/approved/<?= htmlspecialchars($row['filename']) ?>" width="200">
        <p style="font-size:0.8rem;"><?= htmlspecialchars($row['uploader_name']) ?></p>
    </div>
<?php endwhile; ?>
</div>

<?php include 'includes/footer.php'; ?>
