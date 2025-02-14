<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Solicitud de Prestación de Plazo</title>
    <script>
        function toggleMotivoTextbox() {
            var dropdown = document.getElementById("razon");
            var textbox = document.getElementById("motivo");
            if (dropdown.value === "Otro") {
                textbox.style.display = "block";
            } else {
                textbox.style.display = "none";
                textbox.value = ""; // Clear the textbox value when not needed
            }
        }
    </script>
</head>
<body>
    <form action="procesar_solicitud.php" method="post">
        <label for="fecha_inicial">Fecha Inicial:</label>
        <input type="date" id="fecha_inicial" name="fecha_inicial" required><br><br>

        <label for="fecha_final">Fecha Final:</label>
        <input type="date" id="fecha_final" name="fecha_final" required><br><br>

        <label for="razon">Razón del Plazo:</label>
        <select id="razon" name="motivo" onchange="toggleMotivoTextbox()" required>
            <option value="Incapacidad">Incapacidad</option>
            <option value="Embarazo">Embarazo</option>
            <option value="Permiso por Duelo">Permiso por duelo</option>
            <option value="Otro">Otro</option>
        </select><br><br>

        <label for="razon_social">¿Es por una razón social?</label>
        <input type="checkbox" id="razon_social" name="razon_social" value="1"><br><br>

        <input type="text" id="motivo" name="motivo" placeholder="Indique el motivo" style="display:none;"><br><br>

        <input type="submit" value="Enviar Solicitud">
    </form>
</body>
</html>

<?php

require_once("conn.php");
session_start();

$fechaInicial = $_POST['fecha_inicial'];
$fechaFinal = $_POST['fecha_final'];
$motivo = $_POST['motivo'];
$quitarDias = $_POST['razon_social'] ?? 0;

if ($quitarDias) {
    $datetime1 = new DateTime($fechaInicial);
    $datetime2 = new DateTime($fechaFinal);
    $interval = $datetime1->diff($datetime2);
    $dias = $interval->days;

    $queryCD = $conn->prepare("SELECT Dias FROM empleado WHERE Numero_Empleado = ?");
    $queryCD->bind_param("i", $_SESSION['Numero_Empleado']);
    $queryCD->execute();
    $resultCD = $queryCD->get_result();
    $rowCD = $resultCD->fetch_assoc();

    if ($rowCD['Dias'] >= $dias) {
          
        $queryUD = $conn->prepare("UPDATE empleado SET Dias = Dias - ? WHERE Numero_Empleado = ?");
        $queryUD->bind_param("ii", $dias, $_SESSION['Numero_Empleado']);
        $queryUD->execute();
        $queryUD->close();

    } 
    else {
        
        echo "<script>alert('No tienes suficientes días disponibles para esta solicitud.'); window.location.href='SOLICITUDprestacionplazo.php';</script>";
        exit();
        
    }
}

$queryInsertarP = $conn->prepare("INSERT INTO prestacion (Tipo, Fecha_Solicitada) VALUES ('Plazo', CURRENT_DATE)");
$queryInsertarP->execute();
$Id_Prestacion = $conn->insert_id;
$queryInsertarP->close();

$queryInsertarPE = $conn->prepare("INSERT INTO empleado_prestacion (Numero_Empleado, Id_Prestacion, Tipo, Fecha_Solicitada) VALUES (?, ?, ?, CURRENT_DATE)");
$tipo = 'Plazo';
$queryInsertarPE->bind_param("iis", $_SESSION['Numero_Empleado'], $Id_Prestacion, $tipo);
$queryInsertarPE->execute();
$queryInsertarPE->close();

$queryInsertarPP = $conn->prepare("INSERT INTO prestacion_plazos (Id_Prestacion, Numero_Empleado, Fecha_Inicial, Fecha_Final, Motivo) VALUES (?, ?, ?, ?, ?)");
$queryInsertarPP->bind_param("iss", $Id_Prestacion, $_SESSION['Numero_Empleado'], $fechaInicial, $fechaFinal, $motivo);
$queryInsertarPP->execute();
$queryInsertarPP->close();

echo "<script>alert('Solicitud de prestación de plazo enviada correctamente.'); window.location.href='index.php';</script>";
exit();


?>