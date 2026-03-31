<?php
include 'includes/header.php';
include 'includes/db.php';

// Pobieranie zatwierdzonych zdjęć przez PDO
$res = $db->query("SELECT * FROM photos WHERE status='approved' ORDER BY uploaded_at DESC");
?>

<div class="section-card">
    <h2>Galeria zdjęć</h2>
    
    <div style="margin-bottom: 40px; text-align: center; display: flex; justify-content: center; gap: 15px; flex-wrap: wrap;">
        
        <a href="upload.php" class="btn-elegant">
            Prześlij swoje zdjęcie
        </a>

        <a href="download_all.php" class="btn-elegant" style="background-color: #8c7e6d; display: inline-flex; align-items: center;">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 10px;">
                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                <polyline points="7 10 12 15 17 10"></polyline>
                <line x1="12" y1="15" x2="12" y2="3"></line>
            </svg>
            Pobierz całą galerię (.zip)
        </a>
    </div>

    <div class="gallery-container" style="display:flex; flex-wrap:wrap; gap:20px; justify-content: center;">
        <?php while ($row = $res->fetch(PDO::FETCH_ASSOC)): ?>
            <div class="gallery-item" style="text-align: center; width: 220px;">
                <img src="uploads/approved/<?= htmlspecialchars($row['filename']) ?>" 
                     class="gallery-img"
                     alt="Zdjęcie weselne"
                     onclick="openLightbox(this.src)"
                     style="width: 100%; height: 180px; object-fit: cover; border-radius: 12px; cursor: pointer; display: block; box-shadow: var(--shadow-soft);">
            </div>
        <?php endwhile; ?>
    </div>
</div>

<div class="lightbox-overlay" id="lightbox" onclick="closeLightbox()">
    
    <button class="lightbox-arrow prev" id="prevBtn" onclick="changeImage(-1); event.stopPropagation();"></button>
    
    <div class="lightbox-frame" onclick="event.stopPropagation()" style="text-align: center; position: relative; display: block;">
        
        <a href="" id="downloadBtn" download 
           style="position: absolute; top: 15px; right: 15px; z-index: 10; cursor: pointer; background: rgba(255,255,255,0.8); border-radius: 50%; padding: 8px; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 10px rgba(0,0,0,0.2); transition: transform 0.2s;">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="color: var(--accent);">
                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                <polyline points="7 10 12 15 17 10"></polyline>
                <line x1="12" y1="15" x2="12" y2="3"></line>
            </svg>
        </a>

        <img src="" id="lightbox-img" class="lightbox-img">
    </div>

    <button class="lightbox-arrow next" id="nextBtn" onclick="changeImage(1); event.stopPropagation();"></button>
</div>

<script>
let currentImages = [];
let currentIndex = 0;

function updateArrows() {
    const prevBtn = document.getElementById('prevBtn');
    const nextBtn = document.getElementById('nextBtn');
    
    if (currentIndex === 0) prevBtn.classList.add('hidden');
    else prevBtn.classList.remove('hidden');
    
    if (currentIndex === currentImages.length - 1) nextBtn.classList.add('hidden');
    else nextBtn.classList.remove('hidden');
}

function openLightbox(src) {
    const imgElements = document.querySelectorAll('.gallery-img');
    currentImages = Array.from(imgElements).map(img => img.src);
    
    currentIndex = currentImages.indexOf(src);
    
    const lightbox = document.getElementById('lightbox');
    const img = document.getElementById('lightbox-img');
    const downloadBtn = document.getElementById('downloadBtn');
    
    img.src = src;
    downloadBtn.href = src;
    
    lightbox.classList.add('active');
    document.body.style.overflow = 'hidden'; 
    
    updateArrows();
}

function changeImage(direction) {
    currentIndex += direction;
    
    if (currentIndex < 0) currentIndex = 0;
    if (currentIndex >= currentImages.length) currentIndex = currentImages.length - 1;
    
    const newSrc = currentImages[currentIndex];
    document.getElementById('lightbox-img').src = newSrc;
    document.getElementById('downloadBtn').href = newSrc; 
    
    updateArrows();
}

function closeLightbox() {
    document.getElementById('lightbox').classList.remove('active');
    document.body.style.overflow = 'auto';
}

document.addEventListener('keydown', (e) => {
    if (!document.getElementById('lightbox').classList.contains('active')) return;
    if (e.key === "ArrowLeft") changeImage(-1);
    if (e.key === "ArrowRight") changeImage(1);
    if (e.key === "Escape") closeLightbox();
});
</script>

<?php include 'includes/footer.php'; ?>