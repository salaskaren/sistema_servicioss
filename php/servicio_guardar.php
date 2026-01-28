<?php
session_start();
include "config.php";

/* VERIFICAR PERMISOS */
$rol = $_SESSION["usuario_rol"] ?? 0;
$permite_admin = in_array($rol, [1, 2, 4]);

if (!$permite_admin) {
    die("Acceso denegado.");
}

/* ================================
   VALIDAR CAMPOS
================================= */
$categoria_id = $_POST["categoria_id"] ?? "";
$nombre = trim($_POST["nombre"] ?? "");
$descripcion = trim($_POST["descripcion"] ?? "");
$icono = trim($_POST["icono"] ?? "");

if ($categoria_id == "" || $nombre == "") {
    echo "<script>alert('Faltan campos obligatorios'); window.location='servicios.php';</script>";
    exit;
}

/* ================================
   PROCESAR IMAGEN
================================= */

$ruta_final = "";

if (!empty($_FILES["imagen"]["name"])) {

    $permitidos = ["image/jpeg", "image/png", "image/jpg"];
    $tipo = $_FILES["imagen"]["type"];
    
    if (!in_array($tipo, $permitidos)) {
        echo "<script>alert('Formato de imagen no válido. Solo JPG y PNG'); window.location='servicios.php';</script>";
        exit;
    }

    // Crear carpeta si no existe
    if (!is_dir("uploads")) {
        mkdir("uploads", 0777, true);
    }

    $nombreArchivo = "srv_" . uniqid() . "_" . basename($_FILES["imagen"]["name"]);
    $ruta = "uploads/" . $nombreArchivo;

    if (move_uploaded_file($_FILES["imagen"]["tmp_name"], $ruta)) {
        $ruta_final = $ruta;
    }
}

/* ================================
   GUARDAR EN BASE DE DATOS
================================= */
$sql = "INSERT INTO subcategorias (categoria_id, nombre, descripcion, icono, imagen)
        VALUES (?, ?, ?, ?, ?)";

$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "issss", $categoria_id, $nombre, $descripcion, $icono, $ruta_final);

if (mysqli_stmt_execute($stmt)) {

    echo "<script>
        alert('Servicio creado correctamente');
        window.location='servicios.php';
    </script>";
    exit;

} else {
    echo "<script>
        alert('Error al guardar en la base de datos');
        window.location='servicios.php';
    </script>";
    exit;
}
?>
