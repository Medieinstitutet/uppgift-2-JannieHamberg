<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

function checkLogin() {
    if (!isset($_SESSION['user_id'])) {
        header('Location: /pages/login.php');
        exit();
    }
}
?>
