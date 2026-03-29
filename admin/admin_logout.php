<?php
session_start();

// Usuwamy TYLKO dane logowania administratora
unset($_SESSION['admin_logged_in']);
unset($_SESSION['admin_username']);

// Przekierowanie na stronę logowania admina z komunikatem
header("Location: /wedding_hub/admin/admin_login.php?logged_out=1");
exit;
