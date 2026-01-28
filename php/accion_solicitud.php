<?php
session_start();
include "config.php";

/* ===================== PROTECCIÓN ===================== */
$rol = $_SESSION["usuario_rol"] ?? 0;
if (!in_array($rol, [1, 2, 4])) {
    die("Acceso denegado");
}

$id = intval($_POST["id"] ?? 0);
$accion = $_POST["accion"] ?? "";

if (!$id || !$accion) {
    header("Location: solicitudes.php");
    exit;
}

/* ===================== APROBAR ===================== */
if ($accion === "aprobar") {

    // 1️⃣ Obtener solicitud
    $q = mysqli_query($conn, "
        SELECT * 
        FROM solicitudes_trabajador 
        WHERE id = $id AND estado = 'pendiente'
    ");

    if ($s = mysqli_fetch_assoc($q)) {

        // 2️⃣ Insertar como trabajador ACTIVO
        $insert = mysqli_query($conn, "
            INSERT INTO trabajadores (
                usuario_id,
                subcategoria_id,
                nombre_completo,
                ci,
                descripcion,
                ubicacion,
                foto_perfil,
                estado
            ) VALUES (
                '{$s['usuario_id']}',
                '{$s['subcategoria_id']}',
                '{$s['nombre_completo']}',
                '{$s['ci']}',
                '{$s['descripcion']}',
                '{$s['ubicacion']}',
                '{$s['foto_perfil']}',
                'activo'
            )
        ");

        // 🔴 Si falla, mostrar error real
        if (!$insert) {
            die("Error al crear trabajador: " . mysqli_error($conn));
        }

        // 3️⃣ Marcar solicitud como aprobada
        mysqli_query($conn, "
            UPDATE solicitudes_trabajador 
            SET estado = 'aprobado'
            WHERE id = $id
        ");
    }
}

/* ===================== RECHAZAR ===================== */
if ($accion === "rechazar") {
    mysqli_query($conn, "
        UPDATE solicitudes_trabajador 
        SET estado = 'rechazado'
        WHERE id = $id
    ");
}

/* ===================== ELIMINAR ===================== */
if ($accion === "eliminar") {
    mysqli_query($conn, "
        DELETE FROM solicitudes_trabajador 
        WHERE id = $id
    ");
}

header("Location: solicitudes.php");
exit;
