<?php
session_start();
include "config.php";

if (!isset($_GET['id'])) {
    die("Subcategoría no especificada.");
}

$subcategoria_id = intval($_GET['id']);

// Obtener información de la subcategoría
$sql_sub = "SELECT nombre FROM subcategorias WHERE id = $subcategoria_id";
$res_sub = $conn->query($sql_sub);
$subcategoria = $res_sub->fetch_assoc();

// Obtener trabajadores de esta subcategoría
$sql = "SELECT * 
        FROM trabajadores 
        WHERE subcategoria_id = $subcategoria_id AND estado = 'activo'";

$trabajadores = $conn->query($sql);

?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Trabajadores - <?= $subcategoria['nombre'] ?></title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body {
    background: linear-gradient(to bottom, #0a1a2a, #2c3e50);
    color: white;
    padding-bottom: 50px;
}

.card-worker {
    background: #ffffff;
    color: #0a1a2a;
    border-radius: 14px;
    padding: 20px;
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    transition: 0.3s;
}
.card-worker:hover {
    transform: translateY(-6px);
}

.card-worker img {
    width: 100%;
    height: 180px;
    object-fit: cover;
    border-radius: 12px;
}
</style>

</head>
<body>

<div class="container mt-4">

    <a href="subcategorias.php" class="btn btn-light mb-3">⬅ Volver</a>

    <h2 class="text-center mb-4">
        Trabajadores en <span class="text-warning"><?= $subcategoria['nombre'] ?></span>
    </h2>

    <div class="row g-4">

        <?php if ($trabajadores->num_rows > 0): ?>
            <?php while ($t = $trabajadores->fetch_assoc()): ?>
                <div class="col-md-4">
                    <div class="card-worker">

                        <img src="<?= $t['foto_perfil'] ? $t['foto_perfil'] : 'assets/default.png' ?>" alt="Foto">

                        <h4 class="mt-3"><?= $t['nombre_completo'] ?></h4>
                        <p><strong>CI:</strong> <?= $t['ci'] ?></p>

                        <p><?= nl2br($t['descripcion']) ?></p>

                        <p><strong>Ubicación:</strong><br><?= $t['ubicacion'] ?></p>

                        <a href="tel:<?= $t['ci'] ?>" class="btn btn-primary mt-2 w-100">
                            Contactar
                        </a>

                    </div>
                </div>
            <?php endwhile; ?>

        <?php else: ?>
            <h4 class="text-center">No hay trabajadores activos aún.</h4>
        <?php endif; ?>

    </div>

</div>

</body>
</html>
