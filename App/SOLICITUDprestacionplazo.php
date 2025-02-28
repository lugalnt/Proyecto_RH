<?php
header('Content-Type: application/json');
require_once("conn.php");
session_start();

// Leer el contenido JSON de la solicitud
$json = file_get_contents("php://input");
$data = json_decode($json, true);

// Verificar que se hayan recibido los datos
$fechaInicial = isset($data['fecha_inicial']) ? $data['fecha_inicial'] : '';
$motivo = isset($data['motivo']) ? $data['motivo'] : '';
$otroMotivo = isset($data['otro']) ? $data['otro'] : '';
$usarDiasExtra = isset($data['usarDiasExtra']) ? $data['usarDiasExtra'] : '0';

if (empty($fechaInicial)) {
    echo json_encode(["success" => false, "message" => "Fecha inicial no proporcionada."]);
    exit();
}

// Si el usuario seleccionó "Otro", usar el motivo personalizado
if (!empty($otroMotivo)) {
    $motivo = $otroMotivo;
}

// Convertir la fecha inicial de "MM/DD/YYYY" a "YYYY-MM-DD"
$fechaObj = DateTime::createFromFormat("m/d/Y", $fechaInicial);
if (!$fechaObj) {
    echo json_encode(["success" => false, "message" => "Formato de fecha inicial inválido."]);
    exit();
}
$fechaInicialFormatted = $fechaObj->format("Y-m-d");

// Calcular la fecha final sumando 3 días hábiles a la fecha inicial
$startDate = new DateTime($fechaInicialFormatted);
$dias = 0;
while ($dias < 3) {
    $startDate->modify('+1 day');
    if ($startDate->format('N') < 6) { // Solo días hábiles (lunes=1 a viernes=5)
        $dias++;
    }
}
$fechaFinal = $startDate->format("Y-m-d");

// Si se quiere usar días extra, verificar que el empleado tenga suficientes días
if ($usarDiasExtra == "1") {
    $queryCD = $conn->prepare("SELECT Dias FROM empleado WHERE Numero_Empleado = ?");
    $queryCD->bind_param("i", $_SESSION['Numero_Empleado']);
    $queryCD->execute();
    $resultCD = $queryCD->get_result();
    $rowCD = $resultCD->fetch_assoc();
    $queryCD->close();

    if ($rowCD['Dias'] < 3) {
        echo json_encode(["success" => false, "message" => "No tienes suficientes días disponibles."]);
        exit();
    }
    // Descontar 3 días disponibles
    $queryUpdate = $conn->prepare("UPDATE empleado SET Dias = Dias - 3 WHERE Numero_Empleado = ?");
    $queryUpdate->bind_param("i", $_SESSION['Numero_Empleado']);
    $queryUpdate->execute();
    $queryUpdate->close();
}

// Insertar en la tabla 'prestacion'
$queryInsertarP = $conn->prepare("INSERT INTO prestacion (Tipo, Fecha_Solicitada) VALUES ('Plazo', CURRENT_DATE)");
$queryInsertarP->execute();
$Id_Prestacion = $conn->insert_id;
$queryInsertarP->close();

// Insertar en 'empleado_prestacion'
$queryInsertarPE = $conn->prepare("INSERT INTO empleado_prestacion (Numero_Empleado, Id_Prestacion, Tipo, Fecha_Solicitada) VALUES (?, ?, 'Plazo', CURRENT_DATE)");
$queryInsertarPE->bind_param("ii", $_SESSION['Numero_Empleado'], $Id_Prestacion);
$queryInsertarPE->execute();
$queryInsertarPE->close();

// Insertar en 'prestacion_plazos'
$queryInsertarPP = $conn->prepare("INSERT INTO prestacion_plazos (Id_Prestacion, Numero_Empleado, Fecha_Inicio, Fecha_Final, Tipo) VALUES (?, ?, ?, ?, ?)");
$queryInsertarPP->bind_param("issss", $Id_Prestacion, $_SESSION['Numero_Empleado'], $fechaInicialFormatted, $fechaFinal, $motivo);
$queryInsertarPP->execute();
$queryInsertarPP->close();

echo json_encode(["success" => true, "message" => "Solicitud enviada correctamente.", "fecha_final" => $fechaFinal]);
exit();
?>
