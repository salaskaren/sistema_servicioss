<?php
include "config.php";

if (!isset($_GET['categoria_id']) || !is_numeric($_GET['categoria_id'])) {
    echo '<option value="">Categoría inválida</option>';
    exit;
}

$categoria_id = (int) $_GET['categoria_id'];

$result = mysqli_query(
    $conn,
    "SELECT id, nombre 
     FROM subcategorias 
     WHERE categoria_id = $categoria_id
     ORDER BY nombre ASC"
);

if (!$result || mysqli_num_rows($result) === 0) {
    echo '<option value="">No hay subcategorías</option>';
    exit;
}

echo '<option value="">Seleccione una subcategoría</option>';

while ($row = mysqli_fetch_assoc($result)) {
    echo '<option value="'.$row['id'].'">'
        . htmlspecialchars($row['nombre'], ENT_QUOTES, 'UTF-8') .
        '</option>';
}
