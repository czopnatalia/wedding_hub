<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Jeśli admin już jest zalogowany, nie pokazuj mu formularza, tylko przenieś do dashboardu
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header("Location: /wedding_hub/admin/dashboard.php");
    exit;
}

include '../includes/header.php'; 
?>

<style>
.login-panel {
    max-width: 500px;
    margin: 60px auto;
    padding: 32px 30px;
    border-radius: var(--radius-lg);
    background: var(--bg-card);
    backdrop-filter: blur(18px);
    box-shadow: var(--shadow-soft);
    text-align: center;
}

.login-input::placeholder {
    color: var(--text-muted);
}

.login-input {
    width: 100%;
    padding: 12px;
    margin-bottom: 18px;
    border-radius: 12px;
    border: 1px solid var(--accent);
    color: var(--text-main);
}


.login-btn {
    background: var(--accent);
    color: #000;
    padding: 12px 22px;
    border-radius: 12px;
    border: none;
    cursor: pointer;
    font-weight: 600;
    transition: 0.3s;
}

.login-btn:hover {
    background: #ffe2b3;
}

.error-msg {
    color: #ff8080;
    margin-bottom: 15px;
}
</style>

<div class="section-card" style="max-width:500px; margin:60px auto; text-align:center;">

    <h2>Panel administratora</h2>

    <?php if (isset($_GET['error'])): ?>
        <div style="color:#ff8080; margin-bottom:15px;">
            Nieprawidłowy login lub hasło
        </div>
    <?php endif; ?>


    <?php if (isset($_GET['logged_out'])): ?>
    <div style="
        background:#ffe2b3;
        padding:12px 18px;
        border-radius:12px;
        margin-bottom:20px;
        font-weight:600;
        text-align:center;
        color:#000;
    ">
        Zostałeś wylogowany.
    </div>
    <?php endif; ?>


    <form action="/wedding_hub/admin/admin_login_check.php" method="POST">
        <input type="text" name="username" class="login-input" placeholder="Login" required>
        <input type="password" name="password" class="login-input" placeholder="Hasło" required>
        <button type="submit" class="login-btn">Zaloguj</button>
    </form>

</div>

<?php include '../includes/footer.php'; ?>
