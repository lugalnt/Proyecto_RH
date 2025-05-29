<?php

// Ahora los documentos se obtienen de la base de datos, no de un array fijo

function queDocumentos($prestacion) {
    global $conn;
    $stmt = $conn->prepare("SELECT documentos FROM tiposprestacion WHERE nombre = ?");
    $stmt->bind_param("s", $prestacion);
    $stmt->execute();
    $stmt->bind_result($documentos);
    if ($stmt->fetch() && $documentos) {
        $stmt->close();
        return $documentos;
    } else {
        $stmt->close();
        return "Prestación no encontrada o sin documentos definidos.";
    }
}
?>