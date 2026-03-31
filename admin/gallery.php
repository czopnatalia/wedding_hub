<?php
session_start();

// 1. Najpierw sprawdzamy uprawnienia admina
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin_login.php");
    exit;
}

require '../includes/db.php';

// 2. LOGIKA PRZED WYŚWIETLENIEM CZEGOKOLWIEK (Zatwierdzanie/Usuwanie)
if (isset($_GET['approve']) || isset($_GET['delete'])) {
    
    // AKCEPTACJA
    if (isset($_GET['approve'])) {
        $id = (int)$_GET['approve'];
        $stmt = $db->prepare("SELECT filename FROM photos WHERE id = ?");
        $stmt->execute([$id]);
        $photo = $stmt->fetch();

        if ($photo) {
            $from = "../uploads/pending/" . $photo['filename'];
            $to = "../uploads/approved/" . $photo['filename'];
            if (file_exists($from)) rename($from, $to);
            $db->prepare("UPDATE photos SET status = 'approved' WHERE id = ?")->execute([$id]);
        }
    }

    // USUWANIE (działa dla obu folderów)
    if (isset($_GET['delete'])) {
        $id = (int)$_GET['delete'];
        $stmt = $db->prepare("SELECT filename FROM photos WHERE id = ?");
        $stmt->execute([$id]);
        $photo = $stmt->fetch();

        if ($photo) {
            $f1 = "../uploads/pending/" . $photo['filename'];
            $f2 = "../uploads/approved/" . $photo['filename'];
            if (file_exists($f1)) unlink($f1);
            if (file_exists($f2)) unlink($f2);
            $db->prepare("DELETE FROM photos WHERE id = ?")->execute([$id]);
        }
    }

    // Po operacji odświeżamy stronę
    header("Location: gallery.php");
    exit;
}

// 3. POBIERANIE DANYCH
$pending = $db->query("SELECT * FROM photos WHERE status = 'pending' ORDER BY uploaded_at DESC")->fetchAll();
$approved = $db->query("SELECT * FROM photos WHERE status = 'approved' ORDER BY uploaded_at DESC")->fetchAll();

// 4. DOPIERO TERAZ DOŁĄCZAMY NAGŁÓWEK I HTML
include '../includes/header.php';
?>

<div class="section-card" style="max-width: 1200px;">
    <h2>Zarządzanie Galerią</h2>
    <div style="text-align: center; margin-bottom: 30px;">
        <a href="admin/dashboard.php" class="btn-elegant">← Powrót do panelu</a>
    </div>

    <h3 style="color: var(--accent); border-bottom: 1px solid var(--accent-soft); padding-bottom: 10px;">
        Oczekujące na akceptację (<?= count($pending) ?>)
    </h3>
    <div style="display: flex; flex-wrap: wrap; gap: 20px; margin-bottom: 50px; justify-content: center;">
        <?php if (!$pending): ?>
            <p style="color: var(--text-muted); font-style: italic;">Brak nowych zdjęć do sprawdzenia.</p>
        <?php endif; ?>
        
        <?php foreach ($pending as $row): ?>
            <div class="gallery-item" style="width: 200px; background: rgba(255,255,255,0.4); backdrop-filter: blur(5px); padding: 10px; border-radius: 15px; text-align: center;">
                
                <img src="uploads/pending/<?= htmlspecialchars($row['filename']) ?>" 
                     class="moderation-img-pending"
                     onclick="openLightbox(this.src, 'pending')" 
                     style="width: 100%; height: 130px; object-fit: cover; border-radius: 10px; margin-bottom: 10px; cursor: pointer; display: block; box-shadow: 0 4px 10px rgba(0,0,0,0.1);">
                
                <div style="font-size: 0.8rem; margin-bottom: 10px; color: var(--text-main);">
                    Autor: <strong><?= htmlspecialchars($row['uploader_name']) ?></strong>
                </div>
                <div style="display: flex; gap: 5px; flex-direction: column;">
                    <a href="admin/gallery.php?approve=<?= $row['id'] ?>" class="btn-elegant" style="padding: 8px; font-size: 0.8rem; margin: 0; background: #d4b996;">Akceptuj</a>
                    <a href="admin/gallery.php?delete=<?= $row['id'] ?>" onclick="return confirm('Odrzucić i trwale usunąć to zdjęcie?')" class="btn-elegant" style="padding: 8px; font-size: 0.8rem; margin: 0; background: #8c7e6d;">Odrzuć/Usuń</a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <h3 style="color: var(--text-main); border-bottom: 1px solid var(--accent-soft); padding-bottom: 10px;">
        Widoczne w galerii (<?= count($approved) ?>)
    </h3>
    <div style="display: flex; flex-wrap: wrap; gap: 20px; justify-content: center;">
        <?php foreach ($approved as $row): ?>
            <div class="gallery-item" style="width: 180px; background: rgba(255,255,255,0.3); backdrop-filter: blur(5px); padding: 10px; border-radius: 15px; text-align: center;">
                
                <img src="uploads/approved/<?= htmlspecialchars($row['filename']) ?>" 
                     class="moderation-img-approved"
                     onclick="openLightbox(this.src, 'approved')" 
                     style="width: 100%; height: 110px; object-fit: cover; border-radius: 10px; margin-bottom: 10px; opacity: 0.8; cursor: pointer; display: block;">
                
                <div style="font-size: 0.75rem; margin-bottom: 8px; color: var(--text-muted);"><?= htmlspecialchars($row['uploader_name']) ?></div>
                <a href="admin/gallery.php?delete=<?= $row['id'] ?>" onclick="return confirm('Usunąć zdjęcie z publicznej galerii?')" class="btn-elegant" style="padding: 6px; font-size: 0.75rem; margin: 0; background: #8c7e6d; width: 100%;">Usuń z galerii</a>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<div class="lightbox-overlay" id="lightbox" onclick="closeLightbox()" style="z-index: 3000;">
    
    <button class="lightbox-arrow prev" id="prevBtn" onclick="changeImage(-1); event.stopPropagation();"></button>
    
    <div class="lightbox-frame" onclick="event.stopPropagation()" style="text-align: center; position: relative; display: block;">
        <button onclick="closeLightbox()" 
                style="position: absolute; top: 15px; right: 15px; z-index: 10; cursor: pointer; background: rgba(255,255,255,0.8); border: none; border-radius: 50%; padding: 8px; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 10px rgba(0,0,0,0.2); transition: transform 0.2s;">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="color: var(--accent);">
                <line x1="18" y1="6" x2="6" y2="18"></line>
                <line x1="6" y1="6" x2="18" y2="18"></line>
            </svg>
        </button>

        <img src="" id="lightbox-img" class="lightbox-img" style="max-height: 85vh; border-radius: 15px;">
    </div>

    <button class="lightbox-arrow next" id="nextBtn" onclick="changeImage(1); event.stopPropagation();"></button>
</div>

<script>
let currentImages = [];
let currentIndex = 0;

// Funkcja aktualizująca strzałki i blokady
function updateArrows() {
    const prevBtn = document.getElementById('prevBtn');
    const nextBtn = document.getElementById('nextBtn');
    
    // Ukrywanie strzałek na końcach listy
    if (currentIndex === 0) prevBtn.classList.add('hidden');
    else prevBtn.classList.remove('hidden');
    
    if (currentIndex === currentImages.length - 1) nextBtn.classList.add('hidden');
    else nextBtn.classList.remove('hidden');
}

// Otwieranie lightboxa z twardym przypisaniem sekcji (Pending lub Approved)
function openLightbox(src, section) {
    // 1. Znajdź wszystkie zdjęcia TYLKO w tej konkretnej sekcji, żeby admin nie przeskakiwał między 'pending' a 'approved'
    const imgElements = document.querySelectorAll('.moderation-img-' + section);
    currentImages = Array.from(imgElements).map(img => img.src);
    
    // 2. Znajdź numer (index) zdjęcia, które kliknęłaś
    currentIndex = currentImages.indexOf(src);
    
    const lightbox = document.getElementById('lightbox');
    const img = document.getElementById('lightbox-img');
    
    // 3. Ustaw źródło zdjęcia
    img.src = src;
    
    lightbox.classList.add('active');
    document.body.style.overflow = 'hidden'; // Blokada przewijania tła (biel/chmury)
    
    updateArrows();
}

// Zmiana zdjęcia (w lewo/w prawo)
function changeImage(direction) {
    currentIndex += direction;
    
    // Zabezpieczenie przed błędem poza zakresem
    if (currentIndex < 0) currentIndex = 0;
    if (currentIndex >= currentImages.length) currentIndex = currentImages.length - 1;
    
    const newSrc = currentImages[currentIndex];
    document.getElementById('lightbox-img').src = newSrc;
    
    updateArrows();
}

// Zamykanie lightboxa
function closeLightbox() {
    document.getElementById('lightbox').classList.remove('active');
    document.body.style.overflow = 'auto'; // Przywrócenie przewijania
}

// Obsługa klawiatury dla wygody
document.addEventListener('keydown', (e) => {
    if (!document.getElementById('lightbox').classList.contains('active')) return;
    
    if (e.key === "ArrowLeft") changeImage(-1);
    if (e.key === "ArrowRight") changeImage(1);
    if (e.key === "Escape") closeLightbox();
});
</script>

<?php include '../includes/footer.php'; ?>