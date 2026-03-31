<?php
require 'includes/db.php';

// 1. Pobierz nazwy wszystkich zatwierdzonych zdjęć
$stmt = $db->query("SELECT filename FROM photos WHERE status='approved'");
$photos = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (!$photos) {
    die("Brak zdjęć do pobrania.");
}

// 2. Ustawienia pliku ZIP
$zipName = 'Wesele_galeria.zip';
$zip = new ZipArchive();

// 3. Tworzenie tymczasowego pliku ZIP
if ($zip->open($zipName, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
    foreach ($photos as $photo) {
        $filePath = 'uploads/approved/' . $photo['filename'];
        if (file_exists($filePath)) {
            // Dodajemy plik do ZIPa pod jego oryginalną nazwą
            $zip->addFile($filePath, $photo['filename']);
        }
    }
    $zip->close();

    // 4. Wysyłanie pliku do przeglądarki
    header('Content-Type: application/zip');
    header('Content-disposition: attachment; filename=' . $zipName);
    header('Content-Length: ' . filesize($zipName));
    readfile($zipName);

    // 5. Usuwanie tymczasowego ZIPa z serwera po pobraniu
    unlink($zipName);
    exit;
} else {
    die("Błąd przy tworzeniu archiwum ZIP.");
}
?>