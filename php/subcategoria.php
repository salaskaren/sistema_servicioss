<?php
session_start();
include "config.php";

$id_categoria = (int) $_GET["id"];

// Cargar categoría
$catData = mysqli_query($conn, "SELECT * FROM categorias WHERE id=$id_categoria");
$categoria = mysqli_fetch_assoc($catData);

// Si no existe la categoría
if (!$categoria) {
    die("<h2>Categoría no encontrada</h2>");
}

// Cargar subcategorías
$subcategorias = [];
$subQuery = mysqli_query(
    $conn,
    "SELECT * FROM subcategorias WHERE categoria_id=$id_categoria ORDER BY nombre ASC"
);
while ($row = mysqli_fetch_assoc($subQuery)) {
    $subcategorias[] = $row;
}

/* ICONOS POR CATEGORÍA */
$icono = "fa-solid fa-layer-group";
switch (strtolower($categoria['nombre'])) {
    case "derecho": $icono = "fa-solid fa-gavel"; break;
    case "tecnologia":
    case "tecnología": $icono = "fa-solid fa-computer"; break;
    case "salud": $icono = "fa-solid fa-stethoscope"; break;
    case "higiene": $icono = "fa-solid fa-broom"; break;
    case "hogar": $icono = "fa-solid fa-house"; break;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Subcategorías - NOBA</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

<style>
body {
    background:#f4f6f9;
    padding-top:85px;
    font-family:'Segoe UI', sans-serif;
}

/* NAVBAR */
.navbar-custom {
    background-color:#0a1a2a;
    height:85px;
    position:fixed;
    top:0;
    width:100%;
    z-index:1000;
}
.navbar-custom .nav-link, .navbar-brand {
    color:white !important;
    font-size:18px;
}

/* HERO */
.hero-box {
    background: linear-gradient(to right, #0a1a2acc, #0a1a2a),
                url('https://images.pexels.com/photos/3183197/pexels-photo-3183197.jpeg');
    background-size:cover;
    background-position:center;
    padding:70px 20px;
    text-align:center;
    color:white;
    margin-bottom:40px;
    animation: fadeHero .8s ease-out forwards;
    opacity:0;
}
@keyframes fadeHero { to { opacity:1; } }

/* CARDS */
.sub-card {
    background:white;
    padding:25px;
    border-radius:14px;
    text-align:center;
    box-shadow:0 5px 18px rgba(0,0,0,0.08);
    transition:.3s;
    border-left:5px solid #1d4ed8;
    height:100%;
}

.sub-card:hover {
    transform:translateY(-6px);
    box-shadow:0 10px 25px rgba(0,0,0,0.15);
}

.sub-card h5 {
    color:#0a1a2a;
    font-weight:600;
}

.sub-card a {
    text-decoration:none;
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
            <li class="nav-item"><a class="nav-link" href="solicitudes.php">Solicitudes</a></li>
        </ul>
        <a class="navbar-brand">NOBA</a>
    </div>
</nav>

<!-- HERO -->
<section class="hero-box">
    <i class="<?= $icono ?>" style="font-size:50px;"></i>
    <h1 class="fw-bold mt-3">
        Subcategorías de <?= htmlspecialchars($categoria['nombre']) ?>
    </h1>
    <p>Selecciona una subcategoría para ver los trabajadores disponibles</p>
</section>

<div class="container">

<?php if (empty($subcategorias)): ?>
    <div class="alert alert-info text-center">
        No hay subcategorías registradas en esta categoría.
    </div>
<?php else: ?>

<div class="row g-4">
<?php foreach ($subcategorias as $sub): ?>
    <div class="col-md-4">
        <a href="trabajadores.php?id=<?= $sub['id'] ?>">
            <div class="sub-card">
                <h5><?= htmlspecialchars($sub["nombre"]) ?></h5>
                <small class="text-muted">Ver profesionales disponibles</small>
            </div>
        </a>
    </div>
<?php endforeach; ?>
</div>

<?php endif; ?>

</div>

</body>
</html>
