<?php
include 'includes/header.php';
include 'includes/db.php';

$msg = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['uploader_name']);
    $files = $_FILES['photos']; // Zauważ nawiasy w HTML, tutaj pobieramy tablicę
    $count = count($files['name']);
    $uploadedCount = 0;
    $errors = [];

    if ($name !== "" && $count > 0) {
        for ($i = 0; $i < $count; $i++) {
            $fileName = $files['name'][$i];
            $fileTmp  = $files['tmp_name'][$i];
            $fileError = $files['error'][$i];

            if ($fileError === 0) {
                $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                if (in_array($ext, ['jpg', 'jpeg', 'png'])) {
                    $newName = time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
                    
                    if (move_uploaded_file($fileTmp, "uploads/pending/$newName")) {
                        // Zapis do bazy za pomocą PDO
                        $stmt = $db->prepare("INSERT INTO photos (filename, uploader_name) VALUES (?, ?)");
                        $stmt->execute([$newName, $name]);
                        $uploadedCount++;
                    } else {
                        $errors[] = "Błąd zapisu pliku: $fileName";
                    }
                } else {
                    $errors[] = "Niedozwolony format: $fileName";
                }
            }
        }
        
        if ($uploadedCount > 0) {
            $msg = "Pomyślnie przesłano $uploadedCount zdjęć do akceptacji.";
        }
        if (!empty($errors)) {
            $msg .= " Wystąpiły błędy: " . implode(", ", $errors);
        }
    } else {
        $msg = "Podaj imię i wybierz przynajmniej jeden plik.";
    }
}
?>

<h2>Prześlij zdjęcie</h2>
<?php if ($msg) echo "<p>$msg</p>"; ?>

<form method="POST" enctype="multipart/form-data">
    <label>Twoje imię:</label>
    <input type="text" name="uploader_name" required>
    
    <label>Zdjęcia (możesz wybrać kilka):</label>
    <input type="file" name="photos[]" multiple required>
    
    <button type="submit" class="btn-elegant">Wyślij zdjęcia</button>
</form>

<?php include 'includes/footer.php'; ?>
