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
    
    /* ZMIANA: Zamiast var(--bg-card) dajemy rgba */
    background: rgba(255, 255, 255, 0.5) !important;
    backdrop-filter: blur(15px) !important;
    -webkit-backdrop-filter: blur(15px);
    
    /* ZMIANA: Subtelniejsza ramka pasująca do szkła */
    border: 1px solid rgba(255, 255, 255, 0.3) !important;
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
    font-weight: 500;
    transition: 0.3s ease;
}

.rsvp-info a:hover {
    color: var(--text-main);
}

/* SEKCJA SZCZEGÓŁÓW */
.details-section {
    max-width: 900px;
    margin: 0 auto;
}

.details-section h3 {
    font-family: "Playfair Display", serif;
    font-size: 1.8rem;
    color: var(--text-main);
    text-align: left;

    /* DODAJ TE TRZY LINIE: */
    border-top: 1px solid rgba(255, 255, 255, 0.3); /* Biała, półprzezroczysta linia */
    padding-top: 30px;                             /* Odstęp tekstu od linii */
    margin-top: 20px;                              /* Odstęp linii od RSVP powyżej */
    margin-bottom: 25px;                           /* Odstęp od treści pod spodem */
}

/* Kontener pojedynczego wiersza szczegółów */
.detail-item-row {
    display: flex;
    align-items: center;
    background: rgba(255, 255, 255, 0.3);
    padding: 15px 20px;
    border-radius: var(--radius-md);
    margin-bottom: 15px;
    gap: 15px;
    transition: transform 0.3s ease, background 0.3s ease;
    border: 1px solid rgba(255, 255, 255, 0.2);
}

.detail-item-row:hover {
    background: rgba(255, 255, 255, 0.5);
    transform: translateX(5px); /* Delikatne wysunięcie w prawo */
}

/* Kółeczko z ikonką (spójne z kontaktem) */
.detail-icon-box {
    width: 45px;
    height: 45px;
    background: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--accent);
    flex-shrink: 0;
    box-shadow: 0 4px 10px rgba(0,0,0,0.05);
}

/* Tekst szczegółów */
.detail-info {
    flex-grow: 1;
    font-size: 1.05rem;
    color: var(--text-main);
    line-height: 1.4;
}

.detail-info strong {
    display: block;
    color: var(--accent);
    text-transform: uppercase;
    font-size: 0.85rem;
    letter-spacing: 1px;
}

/* Przycisk mapy (pinezka) */
.mini-map-btn {
    flex-shrink: 0;
    transition: transform 0.2s ease;
}

.mini-map-btn img {
    width: 30px;
    height: 30px;
    display: block;
}

.mini-map-btn:hover {
    transform: scale(1.2) rotate(10deg);
}

/* RWD - na telefonach */
@media (max-width: 600px) {
    .detail-item-row {
        flex-wrap: wrap;
        justify-content: center;
        text-align: center;
        padding: 20px;
    }
    .detail-info {
        width: 100%;
    }
    .mini-map-btn {
        margin-top: 10px;
    }
}
</style>

<div class="main-panel">

    <div class="rsvp-info">
        Prosimy o potwierdzenie przybycia poprzez formularz w zakładce:
        <a href="rsvp.php">POTWIERDŹ OBECNOŚĆ</a>.
    </div>

    <div class="details-section">
        <h3>Szczegóły uroczystości</h3>

        <div class="detail-item-row">
            <div class="detail-icon-box">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
            </div>
            <div class="detail-info">
                <strong>Ceremonia:</strong> 
                <span>Parafia św. Jana Pawła II w Nowym Sączu - godzina 13:30</span>
            </div>
            <a href="https://maps.google.com/?q=Parafia+Jana+Pawla+II+Nowy+Sacz" target="_blank" class="mini-map-btn" title="Zobacz na mapie">
                <img src="assets/pin.png" alt="mapa">
            </a>
        </div>

        <div class="detail-item-row">
            <div class="detail-icon-box">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 3h12l-2 8H8l-2-8z"></path><line x1="12" y1="11" x2="12" y2="21"></line><line x1="8" y1="21" x2="16" y2="21"></line></svg>
            </div>
            <div class="detail-info">
                <strong>Wesele:</strong> 
                <span>Restauracja Stacja Wola, Wola Kurowska 69</span>
            </div>
            <a href="https://maps.google.com/?q=Stacja+Wola+Wola+Kurowska" target="_blank" class="mini-map-btn" title="Zobacz na mapie">
                <img src="assets/pin.png" alt="mapa">
            </a>
        </div>


        <div class="contact-section" style="margin-top: 60px; text-align: center;">
            <h3 style="font-family: 'Playfair Display', serif; font-size: 1.8rem; margin-bottom: 30px; color: var(--text-main);">
                Kontakt:
            </h3>
            
            <div style="display: flex; justify-content: center; gap: 40px; flex-wrap: wrap;">
                
                <div class="contact-row-item">
                    <div style="font-weight: 600; text-transform: uppercase; letter-spacing: 1px; color: var(--text-main); margin-bottom: 10px;">
                        Natalia
                    </div>
                    <a href="tel:+48513999738" class="contact-link-row">
                        <div class="contact-icon-small">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l2.28-2.28a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
                            </svg>
                        </div>
                        <span>513 999 738</span>
                    </a>
                </div>

                <div class="contact-row-item">
                    <div style="font-weight: 600; text-transform: uppercase; letter-spacing: 1px; color: var(--text-main); margin-bottom: 10px;">
                        Łukasz
                    </div>
                    <a href="tel:+48512899847" class="contact-link-row">
                        <div class="contact-icon-small">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l2.28-2.28a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
                            </svg>
                        </div>
                        <span>512 899 847</span>
                    </a>
                </div>

            </div>
        </div>

    </div>
</div>

<?php include 'includes/footer.php'; ?>
