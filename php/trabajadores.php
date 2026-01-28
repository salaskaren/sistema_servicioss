<?php
session_start();
include "config.php";

/* ===================== VALIDAR ID ===================== */
if (!isset($_GET["id"]) || !is_numeric($_GET["id"])) {
    die("<h3 class='text-center mt-5'>Subcategoría no válida</h3>");
}

$id_subcategoria = (int) $_GET["id"];

/* ===================== OBTENER SUBCATEGORÍA ===================== */
$subQuery = mysqli_query(
    $conn,
    "SELECT s.*, c.nombre AS categoria 
     FROM subcategorias s
     JOIN categorias c ON c.id = s.categoria_id
     WHERE s.id = $id_subcategoria"
);

$subcategoria = mysqli_fetch_assoc($subQuery);

if (!$subcategoria) {
    die("<h3 class='text-center mt-5'>Subcategoría no encontrada</h3>");
}

/* ===================== OBTENER TRABAJADORES ===================== */
$trabajadores = [];
$trabQuery = mysqli_query(
    $conn,
    "SELECT * FROM trabajadores 
     WHERE subcategoria_id = $id_subcategoria 
     AND estado = 'activo'
     ORDER BY nombre_completo ASC"
);

while ($row = mysqli_fetch_assoc($trabQuery)) {
    $trabajadores[] = $row;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title><?= htmlspecialchars($subcategoria['nombre']) ?> - NOBA</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

<style>
body {
    background: #f4f6f9;
    padding-top: 85px;
    font-family: 'Segoe UI', sans-serif;
}

/* NAVBAR */
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
}

/* HERO */
.hero-box {
    background: linear-gradient(to right, #0a1a2acc, #0a1a2a);
    padding: 60px 20px;
    text-align: center;
    color: white;
    margin-bottom: 40px;
}

/* TARJETAS */
.worker-card {
    background: white;
    border-radius: 18px;
    box-shadow: 0 6px 18px rgba(0,0,0,.08);
    transition: .3s;
    height: 100%;
}
.worker-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 12px 26px rgba(0,0,0,.15);
}

.worker-img {
    width: 100%;
    height: 220px;
    object-fit: cover;
    border-top-left-radius: 18px;
    border-top-right-radius: 18px;
}

.worker-body {
    padding: 20px;
}

.worker-body h5 {
    color: #0a1a2a;
    font-weight: 700;
}
</style>
</head>

<body>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-custom">
    <div class="container-fluid">
        <ul class="navbar-nav me-auto">
            <li class="nav-item"><a class="nav-link" href="index.php">Inicio</a></li>
            <li class="nav-item"><a class="nav-link" href="servicios.php">Servicios</a></li>
        </ul>
        <a class="navbar-brand">NOBA</a>
    </div>
</nav>

<!-- HERO -->
<section class="hero-box">
    <h1 class="fw-bold"><?= htmlspecialchars($subcategoria['nombre']) ?></h1>
    <p><?= htmlspecialchars($subcategoria['categoria']) ?></p>
</section>

<div class="container">

<?php if (empty($trabajadores)): ?>
    <div class="alert alert-info text-center">
        Aún no hay profesionales registrados en esta subcategoría.
    </div>
<?php else: ?>

<div class="row g-4">

<?php foreach ($trabajadores as $t): ?>
<div class="col-md-4">
    <div class="worker-card">

        <img 
            src="<?= $t['foto_perfil'] ? htmlspecialchars($t['foto_perfil']) : 'assets/default-user.jpg' ?>" 
            class="worker-img"
        >

        <div class="worker-body">
            <h5><?= htmlspecialchars($t['nombre_completo']) ?></h5>

            <p class="text-muted mb-1">
                <i class="fa-solid fa-id-card"></i> CI: <?= htmlspecialchars($t['ci']) ?>
            </p>

            <p><?= nl2br(htmlspecialchars($t['descripcion'])) ?></p>

            <p class="text-muted">
                <i class="fa-solid fa-location-dot"></i>
                <?= htmlspecialchars($t['ubicacion']) ?>
            </p>
        </div>

    </div>
</div>
<?php endforeach; ?>

</div>

<?php endif; ?>

</div>

</body>
</html>
