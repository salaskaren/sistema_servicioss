<?php
session_start();
include "config.php";

if (!isset($_SESSION["usuario_id"])) {
    header("Location: login.php");
    exit;
}

/* ==========================
   GUARDAR PROFESIONAL
========================== */
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $usuario_id      = $_SESSION["usuario_id"];
    $nombre          = mysqli_real_escape_string($conn, $_POST["nombre_completo"]);
    $ci              = mysqli_real_escape_string($conn, $_POST["ci"]);
    $subcategoria_id = (int) $_POST["subcategoria_id"];
    $descripcion     = mysqli_real_escape_string($conn, $_POST["descripcion"]);
    $ubicacion       = mysqli_real_escape_string($conn, $_POST["ubicacion"]);

    /* ===== FOTO ===== */
    $foto_ruta = null;

    if (!empty($_FILES["foto"]["name"])) {

        $carpeta = "upload/";
        if (!is_dir($carpeta)) {
            mkdir($carpeta, 0777, true);
        }

        $extension = pathinfo($_FILES["foto"]["name"], PATHINFO_EXTENSION);
        $nombreFoto = "perfil_" . time() . "_" . rand(1000,9999) . "." . $extension;
        $destino = $carpeta . $nombreFoto;

        if (move_uploaded_file($_FILES["foto"]["tmp_name"], $destino)) {
            $foto_ruta = $destino;
        }
    }

    /* ===== INSERTAR EN BD ===== */
    $sql = "
        INSERT INTO trabajadores 
        (usuario_id, subcategoria_id, nombre_completo, ci, descripcion, ubicacion, foto_perfil, estado)
        VALUES
        ($usuario_id, $subcategoria_id, '$nombre', '$ci', '$descripcion', '$ubicacion', '$foto_ruta', 'activo')
    ";

    mysqli_query($conn, $sql);

    header("Location: index.php");
    exit;
}

/* ==========================
   CARGAR CATEGORÍAS
========================== */
$categorias = [];
$res = mysqli_query($conn, "SELECT * FROM categorias ORDER BY nombre ASC");
while ($row = mysqli_fetch_assoc($res)) {
    $categorias[] = $row;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Asóciate con NOBA</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<style>
body {
    background: linear-gradient(to bottom, #0a1a2a, #2c3e50);
    padding-top:80px;
    font-family:'Segoe UI', sans-serif;
}
.form-box {
    max-width:720px;
    margin:auto;
    background:white;
    padding:40px;
    border-radius:20px;
    box-shadow:0 10px 30px rgba(0,0,0,.15);
}
h3, label {
    color:#0a1a2a !important;
}
</style>
</head>
<body>

<div class="form-box">
    <h3 class="fw-bold text-center mb-4">Asóciate como Profesional</h3>

    <form method="POST" enctype="multipart/form-data">

        <label>Nombre completo</label>
        <input type="text" name="nombre_completo" class="form-control mb-3" required>

        <label>Carnet de identidad</label>
        <input type="text" name="ci" class="form-control mb-3">

        <label>Categoría</label>
        <select name="categoria_id" id="categoria" class="form-control mb-3" required>
            <option value="">Seleccione una categoría</option>
            <?php foreach ($categorias as $c): ?>
                <option value="<?= $c['id'] ?>">
                    <?= htmlspecialchars($c['nombre']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label>Subcategoría</label>
        <select name="subcategoria_id" id="subcategoria" class="form-control mb-3" required disabled>
            <option>Seleccione categoría primero</option>
        </select>

        <label>Descripción del servicio</label>
        <textarea name="descripcion" class="form-control mb-3" rows="4"></textarea>

        <label>Ubicación</label>
        <input type="text" name="ubicacion" class="form-control mb-3">

        <label>Foto de perfil</label>
        <input type="file" name="foto" class="form-control mb-4" accept="image/*">

        <button class="btn btn-primary w-100 btn-lg">
            Registrar mi servicio
        </button>
    </form>
</div>

<script>
$("#categoria").on("change", function () {
    let categoriaId = $(this).val();

    if (categoriaId !== "") {
        $("#subcategoria").prop("disabled", true)
            .html('<option>Cargando...</option>')
            .load("ajax_subcategorias.php?categoria_id=" + categoriaId, function () {
                $("#subcategoria").prop("disabled", false);
            });
    } else {
        $("#subcategoria").prop("disabled", true)
            .html('<option>Seleccione categoría primero</option>');
    }
});
</script>

</body>
</html>
