<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: /wedding_hub/admin/admin_login.php");
    exit;
}

require_once "includes/db.php";
include 'includes/header.php';

$success = false;
$duplicateMessages = [];

// Upload wielu zdjęć
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['photos'])) {

    // pobierz istniejące hashe z bazy
    $existingHashes = array_map(
        fn($row) => $row['filehash'],
        $db->query("SELECT filehash FROM photos WHERE filehash IS NOT NULL")->fetchAll(PDO::FETCH_ASSOC)
    );

    foreach ($_FILES['photos']['tmp_name'] as $index => $tmpName) {

        if ($_FILES['photos']['error'][$index] === 0 && is_uploaded_file($tmpName)) {

            $originalName = $_FILES['photos']['name'][$index];

            // hash pliku – wykrywanie duplikatów
            $fileHash = md5_file($tmpName);

            if (in_array($fileHash, $existingHashes)) {
                $duplicateMessages[] = "Zdjęcie <strong>{$originalName}</strong> zostało już wcześniej dodane.";
                continue;
            }

            $ext = pathinfo($originalName, PATHINFO_EXTENSION);
            $newName = uniqid() . "." . $ext;
            $target = "uploads/gallery/" . $newName;

            if (move_uploaded_file($tmpName, $target)) {

                $stmt = $db->prepare("
                    INSERT INTO photos (filename, uploader_name, status, filehash)
                    VALUES (?, ?, 'approved', ?)
                ");
                $stmt->execute([$newName, 'admin', $fileHash]);

                $existingHashes[] = $fileHash;
                $success = true;
            }
        }
    }
}

// Pobranie zdjęć
$stmt = $db->query("SELECT * FROM photos ORDER BY id DESC");
$photos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<style>
.section-card {
    max-width: 900px;
    margin: 60px auto;
    padding: 20px 30px;
}

/* --- PRZYCISKI --- */
.return-btn,
.file-btn {
    background: var(--accent);
    color: #000;
    padding: 12px 26px;
    border-radius: 12px;
    cursor: pointer;
    font-weight: 600;
    display: inline-block;
    transition: 0.3s;
    border: none;
    text-transform: uppercase;
}

.return-btn:hover,
.file-btn:hover {
    background: #ffe2b3;
}

/* Ukrywamy input file */
input[type="file"] {
    display: none;
}

/* --- LISTA PLIKÓW --- */
#file-list {
    margin-top: 20px;
}

.file-item {
    display: flex;
    align-items: center;
    background: rgba(255,255,255,0.15);
    padding: 10px 14px;
    border-radius: 10px;
    margin-bottom: 8px;
    font-size: 15px;
}

.file-thumb {
    width: 50px;
    height: 50px;
    object-fit: cover;
    border-radius: 6px;
    margin-right: 12px;
}

.file-name {
    flex-grow: 1;
}

.file-remove {
    cursor: pointer;
    font-weight: bold;
    color: #c0392b;
    font-size: 22px;
}

/* --- KOMUNIKATY --- */
#upload-success {
    background: transparent;
    color: #2e7d32;
    margin-top: 20px;
    font-weight: 600;
    display: <?php echo $success ? "block" : "none"; ?>;
}

.duplicate-info {
    margin-top: 15px;
}

.duplicate-item {
    color: #2e7d32;
    font-weight: 600;
    margin-bottom: 6px;
}

/* --- GALERIA --- */
.gallery-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 18px;
    margin-top: 40px;
}

.gallery-item {
    overflow: hidden;
    border-radius: 12px;
    cursor: pointer;
}

.gallery-item img {
    width: 100%;
    height: 220px;
    object-fit: cover;
    transition: transform .3s ease;
}

.gallery-item:hover img {
    transform: scale(1.06);
}

/* --- LIGHTBOX --- */
#lightbox {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.85);
    display: none;
    align-items: center;
    justify-content: center;
    z-index: 9999;
}

#lightbox img {
    max-width: 90%;
    max-height: 80%;
    border-radius: 10px;
}

.lightbox-btn {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    font-size: 40px;
    color: white;
    cursor: pointer;
    padding: 20px;
    user-select: none;
}

#prev { left: 20px; }
#next { right: 20px; }

#download {
    position: absolute;
    bottom: 40px;
    background: var(--accent);
    color: #000;
    padding: 12px 26px;
    border-radius: 12px;
    font-weight: 600;
    cursor: pointer;
    text-decoration: none;
    transition: 0.3s;
    text-transform: uppercase;
}

#download:hover {
    background: #ffe2b3;
}
</style>

<div class="section-card">
    <h2>Galeria zdjęć</h2>

    <form id="upload-form" method="POST" enctype="multipart/form-data">
        <label for="photos" class="file-btn">Wybierz pliki</label>
        <input type="file" id="photos" name="photos[]" multiple>

        <div id="file-list"></div>

        <button type="submit" class="return-btn" style="margin-top:20px;">Prześlij zdjęcia</button>

        <div id="upload-success">Pliki przesłane pomyślnie!</div>

        <?php if (!empty($duplicateMessages)): ?>
            <div class="duplicate-info">
                <?php foreach ($duplicateMessages as $msg): ?>
                    <div class="duplicate-item"><?= $msg ?></div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </form>

    <div class="gallery-grid">
        <?php foreach ($photos as $p): ?>
            <?php if (!empty($p['filename']) && file_exists("uploads/gallery/" . $p['filename'])): ?>
                <div class="gallery-item">
                    <img src="uploads/gallery/<?= htmlspecialchars($p['filename']) ?>"
                         data-full="uploads/gallery/<?= htmlspecialchars($p['filename']) ?>">
                </div>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>
</div>

<div id="lightbox">
    <span id="prev" class="lightbox-btn">&#10094;</span>
    <img id="lightbox-img">
    <span id="next" class="lightbox-btn">&#10095;</span>
    <a id="download" download>Pobierz zdjęcie</a>
</div>

<script>
let selectedFiles = [];
const photosInput = document.getElementById('photos');
const fileList = document.getElementById('file-list');

photosInput.addEventListener('change', function(e) {
    selectedFiles = Array.from(e.target.files);
    renderFileList();
});

function renderFileList() {
    fileList.innerHTML = "";

    selectedFiles.forEach((file, index) => {
        const item = document.createElement('div');
        item.className = "file-item";

        const thumb = document.createElement('img');
        thumb.className = "file-thumb";
        thumb.src = URL.createObjectURL(file);

        const name = document.createElement('span');
        name.className = "file-name";
        name.textContent = file.name;

        const remove = document.createElement('span');
        remove.className = "file-remove";
        remove.innerHTML = "&times;";
        remove.onclick = () => removeFile(index);

        item.appendChild(thumb);
        item.appendChild(name);
        item.appendChild(remove);

        fileList.appendChild(item);
    });
}

function removeFile(index) {
    selectedFiles.splice(index, 1);
    renderFileList();

    const dataTransfer = new DataTransfer();
    selectedFiles.forEach(f => dataTransfer.items.add(f));
    photosInput.files = dataTransfer.files;
}

/* --- LIGHTBOX --- */
let images = [];
let index = 0;

function initGalleryImages() {
    images = [...document.querySelectorAll('.gallery-item img')];

    images.forEach((img, i) => {
        img.addEventListener('click', () => {
            index = i;
            showImage();
        });
    });
}

function showImage() {
    const src = images[index].dataset.full;
    document.getElementById('lightbox-img').src = src;
    document.getElementById('download').href = src;
    document.getElementById('lightbox').style.display = 'flex';
}

document.getElementById('prev').onclick = () => {
    index = (index - 1 + images.length) % images.length;
    showImage();
};

document.getElementById('next').onclick = () => {
    index = (index + 1) % images.length;
    showImage();
};

document.getElementById('lightbox').onclick = (e) => {
    if (e.target.id === 'lightbox') {
        document.getElementById('lightbox').style.display = 'none';
    }
};

/* --- STRZAŁKI KLAWIATURY + ESC --- */
document.addEventListener('keydown', function(e) {
    const lb = document.getElementById('lightbox');
    if (lb.style.display !== 'flex') return;

    if (e.key === "ArrowRight") {
        index = (index + 1) % images.length;
        showImage();
    }
    if (e.key === "ArrowLeft") {
        index = (index - 1 + images.length) % images.length;
        showImage();
    }
    if (e.key === "Escape") {
        lb.style.display = 'none';
    }
});

document.addEventListener('DOMContentLoaded', initGalleryImages);
</script>

<?php include 'includes/footer.php'; ?>
