CREATE TABLE guests (
    id INT AUTO_INCREMENT PRIMARY KEY,

    -- Kod z zaproszenia (np. KOWALSCY2026)
    code VARCHAR(50) NOT NULL,

    -- Imię i nazwisko osoby potwierdzającej
    name VARCHAR(100) NOT NULL,

    -- Czy osoba potwierdza przybycie (1 = tak, 0 = nie)
    attending TINYINT(1) NOT NULL,

    -- Dieta
    diet_gluten_free TINYINT(1) DEFAULT 0,
    diet_vege TINYINT(1) DEFAULT 0,
    diet_other VARCHAR(255),

    -- Czy to osoba towarzysząca / członek rodziny
    is_companion TINYINT(1) DEFAULT 0,

    -- Propozycja piosenki
    song_request VARCHAR(255),

    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,

    -- ZABEZPIECZENIE PRZED PODWÓJNYM POTWIERDZENIEM
    UNIQUE KEY unique_guest (code, name)
);

CREATE TABLE photos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    filename VARCHAR(255) NOT NULL,
    uploader_name VARCHAR(100) NOT NULL,
    status ENUM('pending','approved') DEFAULT 'pending',
    uploaded_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL
);

INSERT INTO admins (username, password_hash) VALUES
('admin1', 'admin123'),
('admin2', 'admin123');

ALTER TABLE guests
ADD COLUMN diet_vegan TINYINT(1) DEFAULT 0,
ADD COLUMN diet_lactose TINYINT(1) DEFAULT 0;
