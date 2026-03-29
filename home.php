<?php
include 'includes/header.php';
?>

<style>
/* PANEL GŁÓWNY — SPÓJNY ZE STYLEM SECTION-CARD */
.main-panel {
    width: 100%;
    max-width: 1100px;
    margin: 40px auto 80px;
    padding: 32px 30px;
    border-radius: var(--radius-lg);
    background: var(--bg-card);
    backdrop-filter: blur(18px);
    box-shadow: var(--shadow-soft);

    /* WYŁĄCZAMY HOVER */
    transition: none !important;
}

.main-panel h2 {
    font-family: "Playfair Display", serif;
    font-size: 2rem;
    margin-bottom: 25px;
    color: var(--text-main);
    text-align: left;
}

/* INFORMACJA O RSVP */
.rsvp-info {
    font-size: 1.3rem;
    color: var(--text-main);
    margin-bottom: 40px;
    text-align: center;
}

.rsvp-info a {
    color: var(--accent);
    text-decoration: none;
    font-weight: 600;
    transition: 0.3s ease;
}

.rsvp-info a:hover {
    color: #ffe7c2;
}

/* SEKCJA SZCZEGÓŁÓW */
.details-section {
    max-width: 900px;
    margin: 0 auto;
}

.details-section h3 {
    font-family: "Playfair Display", serif;
    font-size: 1.8rem;
    margin-bottom: 25px;
    color: var(--text-main);
    text-align: left;
}

/* POZYCJE SZCZEGÓŁÓW */
.detail-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 25px;
    padding: 12px 0;
}

.detail-text {
    font-size: 1.2rem;
    color: var(--text-main);
    text-align: left;
    max-width: 80%;
}

/* PINEZKI */
.detail-pin img {
    width: 32px;
    height: 32px;
    opacity: 0.9;
    object-fit: contain;
    transition: 0.3s ease;
    filter: none;
    background: transparent;
}

.detail-pin img:hover {
    transform: scale(1.2);
    opacity: 1;
}

@media (max-width: 700px) {
    .detail-row {
        flex-direction: column;
        align-items: flex-start;
        gap: 10px;
    }
    .detail-pin {
        align-self: flex-end;
    }
}
</style>

<div class="main-panel">

    <div class="rsvp-info">
        Prosimy o potwierdzenie przybycia poprzez formularz w zakładce:
        <a href="rsvp.php">Potwierdź obecność</a>.
    </div>

    <div class="details-section">
        <h3>Szczegóły uroczystości</h3>

        <!-- CEREMONIA -->
        <div class="detail-row">
            <div class="detail-text">
                <strong>Ceremonia:</strong> Parafia św. Jana Pawła II w Nowym Sączu, godzina 13:30
            </div>
            <div class="detail-pin">
                <a href="https://www.google.com/maps/place/Parafia+pw.+%C5%9Bw.+Jana+Paw%C5%82a+II+w+Nowym+S%C4%85czu/@49.6310151,20.7146021,18.58z"
                   target="_blank">
                    <img src="assets/pin.png" alt="pin">
                </a>
            </div>
        </div>

        <!-- WESELE -->
        <div class="detail-row">
            <div class="detail-text">
                <strong>Wesele:</strong> Restauracja Stacja Wola, Wola Kurowska 69, 33-311 Wola Kurowska
            </div>
            <div class="detail-pin">
                <a href="https://www.google.com/maps/place//data=!4m2!3m1!1s0x473df1f8dc959527:0xff4a5349f24640b"
                   target="_blank">
                    <img src="assets/pin.png" alt="pin">
                </a>
            </div>
        </div>

    </div>
</div>

<?php include 'includes/footer.php'; ?>
