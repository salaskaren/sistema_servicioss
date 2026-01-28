<?php
session_start();
include "config.php";

/* ==========================
   VALIDAR SESIÓN
========================== */
if (!isset($_SESSION["usuario_id"])) {
    die("Acceso no autorizado");
}

$usuario_id = $_SESSION["usuario_id"];

/* ==========================
   VALIDAR CAMPOS OBLIGATORIOS
========================== */
if (
    empty($_POST['nombre_completo']) ||
    empty($_POST['categoria_id']) ||
    empty($_POST['subcategoria_id'])
) {
    die("Datos incompletos");
}

/* ==========================
   LIMPIAR DATOS
========================== */
$nombre = mysqli_real_escape_string($conn, $_POST['nombre_completo']);
$ci = mysqli_real_escape_string($conn, $_POST['ci'] ?? '');
$categoria_id = (int) $_POST['categoria_id'];
$subcategoria_id = (int) $_POST['subcategoria_id'];
$descripcion = mysqli_real_escape_string($conn, $_POST['descripcion'] ?? '');
$ubicacion = mysqli_real_escape_string($conn, $_POST['ubicacion'] ?? '');

/* ==========================
   FOTO DE PERFIL
========================== */
$foto_nombre = null;

if (!empty($_FILES['foto']['name'])) {
    $extension = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
    $foto_nombre = time() . "_" . uniqid() . "." . $extension;

    move_uploaded_file(
        $_FILES['foto']['tmp_name'],
        "upload/" . $foto_nombre
    );
}

/* ==========================
   GUARDAR SOLICITUD
========================== */
$sql = "
INSERT INTO solicitudes_trabajador
(
    usuario_id,
    categoria_id,
    subcategoria_id,
    nombre_completo,
    ci,
    descripcion,
    ubicacion,
    foto_perfil,
    estado
)
VALUES
(
    '$usuario_id',
    '$categoria_id',
    '$subcategoria_id',
    '$nombre',
    '$ci',
    '$descripcion',
    '$ubicacion',
    '$foto_nombre',
    'pendiente'
)
";

mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Solicitud enviada</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body {
    background: linear-gradient(to bottom, #0a1a2a, #2c3e50);
    height:100vh;
    display:flex;
    align-items:center;
    justify-content:center;
    font-family:'Segoe UI', sans-serif;
}
.card-msg {
    background:white;
    padding:45px;
    border-radius:20px;
    text-align:center;
    max-width:480px;
    box-shadow:0 12px 35px rgba(0,0,0,.2);
}
</style>
</head>
<body>

<div class="card-msg">
    <h3 class="text-success fw-bold">✅ Solicitud enviada</h3>

    <p class="mt-3">
        Tu solicitud fue enviada correctamente.
    </p>

    <p class="text-muted">
        Será revisada por nuestro equipo.<br>
        <strong>En un plazo máximo de 24 horas</strong> será aceptada o rechazada.
    </p>

    <a href="index.php" class="btn btn-primary mt-3 w-100">
        Volver al inicio
    </a>
</div>

</body>
</html>
