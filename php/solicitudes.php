<?php
session_start();
include "config.php";

/* ===================== PROTECCIÓN DE ACCESO ===================== */
$rol_usuario = $_SESSION["usuario_rol"] ?? 0;
$nombre_usuario = $_SESSION["usuario_nombre"] ?? "";

$roles_permitidos = [1, 2, 4];
if (!in_array($rol_usuario, $roles_permitidos)) {
    header("Location: index.php");
    exit;
}

/* ===================== CARGAR SOLICITUDES ===================== */
$solicitudes = [];

$query = "
    SELECT 
        s.id,
        s.nombre_completo,
        s.ci,
        s.descripcion,
        s.ubicacion,
        s.foto_perfil,
        s.estado,
        s.fecha,
        u.nombre AS usuario,
        c.nombre AS categoria,
        sub.nombre AS subcategoria
    FROM solicitudes_trabajador s
    INNER JOIN usuarios u ON s.usuario_id = u.id
    INNER JOIN categorias c ON s.categoria_id = c.id
    INNER JOIN subcategorias sub ON s.subcategoria_id = sub.id
    ORDER BY s.fecha DESC
";

$res = mysqli_query($conn, $query);
while ($row = mysqli_fetch_assoc($res)) {
    $solicitudes[] = $row;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Gestión de Solicitudes - NOBA</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body {
    background:#f4f6f9;
    padding-top:85px;
    font-family:'Segoe UI', sans-serif;
}

.navbar-custom {
    background:#0a1a2a;
    height:85px;
    position:fixed;
    top:0;
    width:100%;
    z-index:1000;
}

.navbar-custom .nav-link,
.navbar-custom .navbar-brand {
    color:white !important;
    font-size:18px;
}

.table-box {
    background:white;
    padding:30px;
    border-radius:16px;
    box-shadow:0 10px 25px rgba(0,0,0,0.12);
}

.badge-pendiente { background:#f59e0b; }
.badge-aprobado { background:#16a34a; }
.badge-rechazado { background:#dc2626; }
</style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-custom">
    <div class="container-fluid">
        <ul class="navbar-nav me-auto">
            <li class="nav-item"><a class="nav-link" href="index.php">Inicio</a></li>
            <li class="nav-item"><a class="nav-link active" href="solicitudes.php">Solicitudes</a></li>
            <li class="nav-item"><a class="nav-link" href="servicios.php">Servicios</a></li>
        </ul>

        <a class="navbar-brand">NOBA</a>

        <div class="text-white ms-auto">
            👤 <?= htmlspecialchars($nombre_usuario) ?>
            <a href="logout.php" class="btn btn-danger btn-sm ms-3">Salir</a>
        </div>
    </div>
</nav>

<div class="container">
<h2 class="fw-bold mb-4 text-center">Gestión de Solicitudes</h2>

<div class="table-box">

<?php if (empty($solicitudes)): ?>
    <div class="alert alert-info text-center">No hay solicitudes registradas.</div>
<?php else: ?>

<table class="table table-hover align-middle">
<thead class="table-dark">
<tr>
    <th>#</th>
    <th>Usuario</th>
    <th>Profesional</th>
    <th>Categoría</th>
    <th>Subcategoría</th>
    <th>Estado</th>
    <th>Fecha</th>
    <th>Acciones</th>
</tr>
</thead>
<tbody>

<?php foreach ($solicitudes as $s): ?>
<tr>
    <td><?= $s['id'] ?></td>
    <td><?= htmlspecialchars($s['usuario']) ?></td>
    <td><?= htmlspecialchars($s['nombre_completo']) ?></td>
    <td><?= htmlspecialchars($s['categoria']) ?></td>
    <td><?= htmlspecialchars($s['subcategoria']) ?></td>
    <td>
        <span class="badge badge-<?= $s['estado'] ?>">
            <?= ucfirst($s['estado']) ?>
        </span>
    </td>
    <td><?= date("d/m/Y H:i", strtotime($s['fecha'])) ?></td>

    <td>
        <?php if ($s['estado'] === 'pendiente'): ?>
        <form action="accion_solicitud.php" method="POST" class="d-flex gap-1">
            <input type="hidden" name="id" value="<?= $s['id'] ?>">

            <button name="accion" value="aprobar"
                class="btn btn-success btn-sm"
                onclick="return confirm('¿Aprobar esta solicitud?')">
                Aprobar
            </button>

            <button name="accion" value="rechazar"
                class="btn btn-warning btn-sm"
                onclick="return confirm('¿Rechazar esta solicitud?')">
                Rechazar
            </button>

            <button name="accion" value="eliminar"
                class="btn btn-danger btn-sm"
                onclick="return confirm('¿Eliminar definitivamente?')">
                Eliminar
            </button>
        </form>
        <?php else: ?>
            <span class="text-muted">—</span>
        <?php endif; ?>
    </td>
</tr>
<?php endforeach; ?>

</tbody>
</table>

<?php endif; ?>

</div>
</div>
</body>
</html>
