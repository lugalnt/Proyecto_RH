<?php
$targetDir = "uploads/";
if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);

if (isset($_FILES["pdf"]) && $_FILES["pdf"]["error"] == 0) {
    $fileName = basename($_FILES["pdf"]["name"]);
    $targetFile = $targetDir . $fileName;
    $fileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
    if ($fileType != "pdf") {
        echo "Solo se permiten archivos PDF.";
        exit;
    }
    if (move_uploaded_file($_FILES["pdf"]["tmp_name"], $targetFile)) {
        echo "PDF subido correctamente.";
    } else {
        echo "Error al subir el archivo.";
    }
} else {
    echo "No se recibió ningún archivo.";
}
?>