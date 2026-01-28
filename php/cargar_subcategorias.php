<?php
include "config.php";

if (!isset($_GET["id"])) {
    exit;
}

$cat_id = (int) $_GET["id"];

$query = mysqli_query(
    $conn,
    "SELECT id, nombre FROM subcategorias 
     WHERE categoria_id = $cat_id 
     ORDER BY nombre ASC"
);

echo "<option value=''>Selecciona una subcategoría</option>";

while ($row = mysqli_fetch_assoc($query)) {
    echo "<option value='{$row['id']}'>" . htmlspecialchars($row['nombre']) . "</option>";
}
