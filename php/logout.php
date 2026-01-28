<?php
session_start();

// borrar variables
session_unset();

// destruir la sesión
session_destroy();

// eliminar cookie
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// evitar volver con botón "atrás"
header("Cache-Control: no-cache, must-revalidate");
header("Expires: 0");

// redirigir a login
header("Location: login.php");
exit;
?>
