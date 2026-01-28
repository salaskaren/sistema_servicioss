<?php
session_start();
include "config.php";

/* ========================== SESIÓN ========================== */
$nombre_usuario = $_SESSION["usuario_nombre"] ?? null;
$rol_usuario = $_SESSION["usuario_rol"] ?? 0;

/* ========================== CARGAR CATEGORÍAS ========================== */
$categorias = [];
$catQuery = mysqli_query($conn, "SELECT * FROM categorias ORDER BY nombre ASC");
while ($row = mysqli_fetch_assoc($catQuery)) {
    $categorias[] = $row;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Categorías de Servicios - NOBA</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

<style>
    body {
        background-color: #f4f6f9;
        padding-top: 85px;
        font-family: 'Segoe UI', sans-serif;
    }

    .navbar-custom {
        background-color: #0a1a2a;
        height: 85px;
        position: fixed;
        top: 0;
        width: 100%;
        z-index: 1000;
    }

    .navbar-custom .nav-link,
    .navbar-custom .navbar-brand {
        color: white !important;
        font-size: 18px;
    }

    .navbar-custom .nav-link:hover {
        color: #66aaff !important;
        transform: translateY(-2px);
    }

    .navbar-middle {
        position: absolute;
        left: 50%;
        top: 50%;
        transform: translate(-50%, -50%);
        font-size: 28px;
        font-weight: bold;
        color: white;
    }

    /* HERO */
    .hero-servicios {
        background: linear-gradient(to right, #0a1a2acc, #0a1a2a),
                    url('https://images.pexels.com/photos/3183197/pexels-photo-3183197.jpeg');
        background-size: cover;
        background-position: center;
        padding: 70px 20px;
        text-align: center;
        color: white;
        margin-bottom: 30px;
    }

    /* BOTÓN ASOCIARSE */
    .asociarse-box {
        background: white;
        border-radius: 16px;
        padding: 30px;
        text-align: center;
        box-shadow: 0 8px 22px rgba(0,0,0,0.12);
        margin-bottom: 40px;
    }

    .btn-asociarse {
        background: #0a1a2a;
        color: white;
        padding: 14px 26px;
        border-radius: 12px;
        font-size: 18px;
        font-weight: 600;
        transition: .3s;
    }

    .btn-asociarse:hover {
        background: #1d4ed8;
        transform: translateY(-2px);
    }

    /* TARJETAS DE CATEGORÍAS */
    .cat-card {
        background: white;
        padding: 30px;
        border-radius: 18px;
        text-align: center;
        box-shadow: 0 6px 18px rgba(0,0,0,0.08);
        transition: .3s ease;
        cursor: pointer;
        border-left: 6px solid #1d4ed8;
    }

    .cat-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 12px 26px rgba(0,0,0,0.15);
    }

    .cat-icon {
        font-size: 40px;
        margin-bottom: 12px;
        color: #1d4ed8;
        transition: .3s ease;
    }

    .cat-card:hover .cat-icon {
        transform: scale(1.1);
        color: #0a1a2a;
    }
</style>
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-custom position-fixed">
    <div class="container-fluid">

        <ul class="navbar-nav me-auto d-flex align-items-center">
            <li class="nav-item"><a class="nav-link" href="index.php">Inicio</a></li>
            <li class="nav-item"><a class="nav-link active" href="servicios.php">Servicios</a></li>
            <li class="nav-item"><a class="nav-link" href="solicitudes.php">Solicitudes</a></li>
        </ul>

        <a class="navbar-brand navbar-middle">NOBA</a>

        <div class="d-flex align-items-center text-white ms-auto">
            <span class="fw-bold me-3">👤 <?= htmlspecialchars($nombre_usuario) ?></span>
            <a href="logout.php" class="btn btn-danger btn-sm">Salir</a>
        </div>

    </div>
</nav>

<!-- HERO -->
<section class="hero-servicios">
    <h1 class="fw-bold">Categorías de Servicios</h1>
    <p>Seleccione una categoría para ver sus subcategorías y servicios.</p>
</section>

<div class="container">

    <!-- 🔹 BOTÓN ASOCIARSE (SOLO LOGUEADOS) -->
    <?php if ($nombre_usuario): ?>
        <div class="asociarse-box">
            <h4 class="fw-bold mb-2">¿Quieres asociarte a NOBA?</h4>
            <p class="text-muted mb-3">
                Registra tu servicio y comienza a ofrecer tu trabajo a nuevos clientes
            </p>
            <a href="asociarse.php" class="btn btn-asociarse">
                Quiero asociarme como servidor
            </a>
        </div>
    <?php endif; ?>

    <h3 class="mb-4 fw-bold text-center">Categorías Disponibles</h3>

    <div class="row g-4">

        <?php foreach ($categorias as $cat): ?>
            <div class="col-md-4">
                <a href="subcategoria.php?id=<?= $cat['id'] ?>" style="text-decoration:none; color:inherit;">
                    <div class="cat-card">

                        <?php
                        $icono = "fa-solid fa-layer-group";
                        switch (strtolower($cat['nombre'])) {
                            case "derecho": $icono = "fa-solid fa-gavel"; break;
                            case "tecnologia":
                            case "tecnología": $icono = "fa-solid fa-computer"; break;
                            case "salud": $icono = "fa-solid fa-stethoscope"; break;
                            case "higiene": $icono = "fa-solid fa-broom"; break;
                            case "hogar": $icono = "fa-solid fa-house"; break;
                        }
                        ?>

                        <i class="<?= $icono ?> cat-icon"></i>
                        <h4><?= htmlspecialchars($cat['nombre']) ?></h4>

                    </div>
                </a>
            </div>
        <?php endforeach; ?>

    </div>

</div>

</body>
</html>
