<?php
session_start();
include "config.php";

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
<title>Categorías - NOBA</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

<style>
    body {
        background:#f4f6f9;
        padding-top:100px;
    }

    .cat-card {
        background:white;
        padding:30px;
        border-radius:18px;
        text-align:center;
        box-shadow:0 6px 18px rgba(0,0,0,0.08);
        transition:.3s;
        cursor:pointer;
        border-left:6px solid #0a1a2a;
    }

    .cat-card:hover {
        transform:translateY(-6px);
        box-shadow:0 10px 25px rgba(0,0,0,0.12);
    }

    .cat-icon {
        font-size:40px;
        margin-bottom:12px;
        color:#0a1a2a;
    }
</style>
</head>
<body>

<div class="container">
    <h2 class="fw-bold mb-4 text-center">Categorías de Servicios</h2>

    <div class="row g-4">
        <?php foreach ($categorias as $cat): ?>
            <div class="col-md-4">
                <a href="subcategorias.php?id=<?= $cat['id'] ?>" style="text-decoration:none; color:inherit;">
                    <div class="cat-card">
                        <i class="fa-solid fa-layer-group cat-icon"></i>
                        <h4><?= $cat['nombre'] ?></h4>
                    </div>
                </a>
            </div>
        <?php endforeach; ?>
    </div>
</div>

</body>
</html>
