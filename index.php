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
    <title>Natalia i Łukasz</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600&family=Inter:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="icon" type="image/jpeg" href="/wedding_hub/favicon.jpg">
    
    <style>
        body {
            margin: 0;
            height: 100vh;
            /*background: url('assets/hero2.jpg') center/cover no-repeat fixed;*/
            background: radial-gradient(circle at top, #2b2118 0, #0b0806 55%, #050302 100%);
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: "Inter", sans-serif;
        }

        .panel {
            width: 90%;
            max-width: 650px;
            background: rgba(255, 255, 255, 0.55);
            backdrop-filter: blur(14px);
            padding: 45px 50px;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0 25px 60px rgba(0,0,0,0.35);
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
            color: #2b2118;
            white-space: nowrap;
        }

        .date {
            font-size: 1.1rem;
            color: #4a3f36;
            margin-bottom: 35px;
        }

        /* Zegar */
        .countdown {
            display: flex;
            justify-content: center;
            gap: 14px;
            margin-bottom: 35px;
        }

        .time-box {
            background: rgba(255, 255, 255, 0.35);
            backdrop-filter: blur(10px);
            padding: 16px 18px;
            border-radius: 14px;
            min-width: 70px;
            font-size: 1.4rem;
            font-weight: 600;
            color: #2b2118;
            box-shadow: 0 10px 25px rgba(0,0,0,0.25);
        }

        /* Pole hasła */
        input[type="password"] {
            width: 80%;
            max-width: 320px;
            padding: 12px;
            border-radius: 12px;
            border: 1px solid #2b2118;
            background: rgba(255,255,255,0.85);
            font-size: 1rem;
            margin-bottom: 20px;
            outline: none;
        }

        /* Subtelny przycisk */
        button {
            background: #2b2118;
            color: #f5eee7;
            border: none;
            padding: 12px 34px;
            border-radius: 12px;
            font-size: 1.05rem;
            cursor: pointer;
            transition: 0.3s ease;
            box-shadow: 0 8px 20px rgba(0,0,0,0.25);
        }

        button:hover {
            background: #3b2b1f;
            box-shadow: 0 12px 28px rgba(0,0,0,0.35);
        }

        .error {
            color: #b30000;
            margin-top: 12px;
            font-size: 0.9rem;
        }

        @media (max-width: 600px) {
            h1 { font-size: 2.2rem; }
            .time-box { min-width: 55px; font-size: 1.1rem; padding: 12px; }
            .panel { padding: 30px 25px; }
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
