<?php
session_start();

$correct_password = "kury";
$error = "";

$auto_pass = isset($_GET['pass']) ? htmlspecialchars($_GET['pass']) : "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($_POST['password'] === $correct_password) {
        $_SESSION['access'] = true;
        header("Location: home.php");
        exit;
    } else {
        $error = "Nieprawidłowe hasło.";
    }
}
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Natalia & Łukasz</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600&family=Inter:wght@300;400;500&display=swap" rel="stylesheet">

    <style>
        body {
            margin: 0;
            height: 100vh;
            /* ZMIANA: Tło z chmurami zamiast koloru */
            background-image: url('assets/chmury.webp'); 
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: "Inter", sans-serif;
        }

        .panel {
            width: 90%;
            max-width: 650px;
            /* ZMIANA: Półprzezroczyste szkło */
            background: rgba(255, 255, 255, 0.5);
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            
            padding: 45px 50px;
            border-radius: 25px;
            text-align: center;
            border: 1px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 15px 45px rgba(0, 0, 0, 0.1);
            animation: fadeIn 1s ease forwards;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(25px); }
            to { opacity: 1; transform: translateY(0); }
        }

        h1 {
            font-family: "Playfair Display", serif;
            font-size: 3rem;
            letter-spacing: 0.18em;
            margin: 0 0 10px;
            color: #4a3f35;
            white-space: nowrap;
        }

        .date {
            font-size: 1.1rem;
            color: #8c7e6d;
            margin-bottom: 35px;
            text-transform: uppercase;
            letter-spacing: 0.1em;
        }

        /* Zegar */
        .countdown {
            display: flex;
            justify-content: center;
            gap: 14px;
            margin-bottom: 35px;
        }

        .time-box {
            /* ZMIANA: Pudełka czasu też są szklane */
            background: rgba(255, 255, 255, 0.4);
            backdrop-filter: blur(5px);
            
            padding: 16px 18px;
            border-radius: 14px;
            min-width: 75px;
            font-size: 1.5rem;
            font-weight: 600;
            color: #4a3f35;
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        input[type="password"] {
            width: 80%;
            max-width: 320px;
            padding: 14px;
            border-radius: 12px;
            border: 1px solid #d4b996;
            background: rgba(255, 255, 255, 0.8); /* Jaśniejsze tło dla czytelności wpisywania */
            font-size: 1rem;
            margin-bottom: 20px;
            outline: none;
            color: #4a3f35;
            text-align: center;
        }

        button {
            background: #d4b996;
            color: #ffffff;
            border: none;
            padding: 14px 40px;
            border-radius: 12px;
            font-size: 1.05rem;
            cursor: pointer;
            transition: 0.3s ease;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        button:hover {
            background: #c5a985;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(212, 185, 150, 0.4);
        }

        .error {
            color: #c30175;
            margin-top: 15px;
            font-size: 0.95rem;
        }

        @media (max-width: 600px) {
            h1 { font-size: 1.8rem; white-space: normal; }
            .countdown { gap: 8px; }
            .time-box { min-width: 50px; font-size: 1rem; padding: 10px; }
            .panel { padding: 35px 20px; width: 85%; }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const target = new Date("2026-09-18T13:30:00").getTime();
            const dEl = document.getElementById('d');
            const hEl = document.getElementById('h');
            const mEl = document.getElementById('m');
            const sEl = document.getElementById('s');

            setInterval(() => {
                const now = new Date().getTime();
                const diff = target - now;

                if (diff <= 0) {
                    dEl.textContent = hEl.textContent = mEl.textContent = sEl.textContent = "00";
                    return;
                }

                const d = Math.floor(diff / (1000*60*60*24));
                const h = Math.floor((diff / (1000*60*60)) % 24);
                const m = Math.floor((diff / (1000*60)) % 60);
                const s = Math.floor((diff / 1000) % 60);

                dEl.textContent = d;
                hEl.textContent = h.toString().padStart(2,'0');
                mEl.textContent = m.toString().padStart(2,'0');
                sEl.textContent = s.toString().padStart(2,'0');
            }, 1000);
        });
    </script>
</head>

<body>

<div class="panel">
    <h1>Natalia i Łukasz</h1>
    <div class="date">Do ślubu pozostało:</div>

    <div class="countdown">
        <div class="time-box" id="d">00</div>
        <div class="time-box" id="h">00</div>
        <div class="time-box" id="m">00</div>
        <div class="time-box" id="s">00</div>
    </div>

    <form method="POST">
        <input type="password" name="password"
               placeholder="Hasło dostępu"
               value="<?= $auto_pass ?>"
               required>

        <br>
        <button type="submit">Wejdź</button>

        <?php if ($error): ?>
            <p class="error"><?= $error ?></p>
        <?php endif; ?>
    </form>
</div>

</body>
</html>