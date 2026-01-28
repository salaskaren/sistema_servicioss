<?php
session_start();
include "config.php";

$rol = $_SESSION["usuario_rol"] ?? 0;
$es_admin = in_array($rol, [1,2,4]);

if (!$es_admin) {
    header("Location: index.php");
    exit;
}

$sql = "SELECT u.id, u.nombre, u.email, r.nombre AS rol, u.estado
        FROM usuarios u
        INNER JOIN roles r ON u.rol = r.id";

$result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Usuarios</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <h2>Gestión de Usuarios</h2>

    <table class="table table-bordered mt-3">
        <thead class="table-dark">
            <tr>
                <th>ID</th><th>Nombre</th><th>Email</th><th>Rol</th><th>Estado</th>
            </tr>
        </thead>

        <tbody>
        <?php while($row = mysqli_fetch_assoc($result)): ?>
            <tr>
                <td><?= $row["id"] ?></td>
                <td><?= $row["nombre"] ?></td>
                <td><?= $row["email"] ?></td>
                <td><?= $row["rol"] ?></td>
                <td><?= $row["estado"] ?></td>
            </tr>
        <?php endwhile; ?>
        </tbody>

    </table>
</div>

</body>
</html>
