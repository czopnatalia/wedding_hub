<?php
include 'includes/header.php';
include 'includes/db.php';

$msg = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['uploader_name']);
    $file = $_FILES['photo'];
    if ($file['error'] === 0 && $name !== "") {
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (in_array($ext, ['jpg','jpeg','png'])) {
            $new = time().'_'.bin2hex(random_bytes(4)).'.'.$ext;
            if (move_uploaded_file($file['tmp_name'], "uploads/pending/$new")) {
                $stmt = $conn->prepare("INSERT INTO photos (filename, uploader_name) VALUES (?, ?)");
                $stmt->bind_param("ss", $new, $name);
                $stmt->execute();
                $msg = "Zdjęcie wysłane do akceptacji.";
            } else {
                $msg = "Błąd zapisu pliku.";
            }
        } else $msg = "Dozwolone: JPG, PNG.";
    } else $msg = "Podaj imię i wybierz plik.";
}
?>

<h2>Prześlij zdjęcie</h2>
<?php if ($msg) echo "<p>$msg</p>"; ?>

<form method="POST" enctype="multipart/form-data">
    <label>Twoje imię:</label>
    <input type="text" name="uploader_name" required>
    <label>Zdjęcie:</label>
    <input type="file" name="photo" required>
    <button type="submit">Wyślij</button>
</form>

<?php include 'includes/footer.php'; ?>
