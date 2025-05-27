
<?php
require_once("conn.php");

function obtenerPrecioPrestacion($nombrePrestacion) {
    global $conn;
    // Normaliza el nombre para evitar problemas de mayúsculas/minúsculas
    $nombrePrestacion = trim($nombrePrestacion);

    $query = $conn->prepare("SELECT precio FROM tiposprestacion WHERE LOWER(nombre) = LOWER(?) LIMIT 1");
    $query->bind_param("s", $nombrePrestacion);
    $query->execute();
    $query->bind_result($precio);
    if ($query->fetch()) {
        return $precio;
    } else {
        return "Prestación no encontrada.";
    }
}
?>