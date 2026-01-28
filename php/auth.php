<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// SI NO EXISTE SESIÓN → REDIRIGIR AL LOGIN
if (!isset($_SESSION["usuario_id"])) {
    header("Location: login.php");
    exit;
}
?>
