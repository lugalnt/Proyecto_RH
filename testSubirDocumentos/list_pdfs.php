<?php
session_start();
$dir = "uploads/". $_SESSION['Numero_Empleado'] . "/";
$pdfs = [];
if (is_dir($dir)) {
    foreach (scandir($dir) as $file) {
        if (strtolower(pathinfo($file, PATHINFO_EXTENSION)) === "pdf") {
            $pdfs[] = $file;
        }
    }
}
echo json_encode($pdfs);
?>