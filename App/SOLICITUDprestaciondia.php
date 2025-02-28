<?php
require_once("conn.php");
session_start();
header("Content-Type: application/json"); // Respuesta en formato JSON

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fecha = $_POST['fecha']; // Formato: MM/DD/YYYY
    $diaExtra = isset($_POST['diaExtra']) ? 1 : 0;
    $motivo = $_POST['motivo'];
    $otro = isset($_POST['otro']) ? $_POST['otro'] : null;

    // Convertir fecha de MM/DD/YYYY a YYYY-MM-DD
    $fechaConvertida = date("Y-m-d", strtotime(str_replace("/", "-", $fecha)));

    // Verificar días disponibles del empleado
    $queryCheckDias = $conn->prepare("SELECT Dias, Dias_Extras FROM empleado WHERE Numero_Empleado = ?");
    $queryCheckDias->bind_param("i", $_SESSION['Numero_Empleado']);
    $queryCheckDias->execute();
    $queryCheckDias->bind_result($dias, $diasExtras);
    $queryCheckDias->fetch();
    $queryCheckDias->close();

    if ($diaExtra && $diasExtras <= 0) {
        echo json_encode(["success" => false, "message" => "No tienes suficientes días extras disponibles."]);
        exit;
    } elseif (!$diaExtra && $dias <= 0) {
        echo json_encode(["success" => false, "message" => "No tienes suficientes días disponibles."]);
        exit;
    }

    // Verificar solicitudes aprobadas en la misma fecha
    $numeroEmpleado = $_SESSION['Numero_Empleado'];
    $queryCount = $conn->prepare("
        SELECT COUNT(*) as count 
        FROM prestacion_dias pd 
        INNER JOIN empleado_prestacion ep ON pd.Id_Prestacion = ep.Id_Prestacion 
        INNER JOIN empleado e ON ep.Numero_Empleado = e.Numero_Empleado 
        INNER JOIN prestacion p ON pd.Id_Prestacion = p.Id_Prestacion 
        WHERE pd.Motivo = ? AND pd.Fecha_Solicitada = ? 
        AND p.Estado = 'Otorgada' 
        AND e.Area = (SELECT Area FROM empleado WHERE Numero_Empleado = ?)
    ");
    $queryCount->bind_param("ssi", $motivo, $fechaConvertida, $numeroEmpleado);
    $queryCount->execute();
    $resultCount = $queryCount->get_result();
    $rowCount = $resultCount->fetch_assoc();

    if ($rowCount['count'] >= 2) {
        echo json_encode(["success" => false, "message" => "Demasiadas solicitudes aprobadas para esta fecha en tu área."]);
        exit;
    }

    // Verificar si ya hay una solicitud para esa fecha
    $queryCheckFecha = $conn->prepare("SELECT COUNT(*) FROM prestacion_dias WHERE Numero_Empleado = ? AND Fecha_Solicitada = ?");
    $queryCheckFecha->bind_param("is", $numeroEmpleado, $fechaConvertida);
    $queryCheckFecha->execute();
    $queryCheckFecha->bind_result($count);
    $queryCheckFecha->fetch();
    $queryCheckFecha->close();

    if ($count > 0) {
        echo json_encode(["success" => false, "message" => "Ya existe una solicitud de prestación para esta fecha."]);
        exit;
    }

    // Insertar en tabla 'prestacion'
    $queryInsertarP = $conn->prepare("INSERT INTO prestacion (Tipo, Fecha_Solicitada) VALUES ('Día', CURRENT_DATE)");
    $queryInsertarP->execute();
    $Id_Prestacion = $conn->insert_id;
    $queryInsertarP->close();

    // Insertar en 'empleado_prestacion'
    $queryInsertarPE = $conn->prepare("INSERT INTO empleado_prestacion (Numero_Empleado, Id_Prestacion, Tipo, Fecha_Solicitada) VALUES (?, ?, ?, CURRENT_DATE)");
    $tipo = 'Día';
    $queryInsertarPE->bind_param("iis", $numeroEmpleado, $Id_Prestacion, $tipo);
    $queryInsertarPE->execute();
    $queryInsertarPE->close();

    // Insertar en 'prestacion_dias'
    $queryInsertarPF = $conn->prepare("INSERT INTO prestacion_dias (Id_Prestacion, Numero_Empleado, Fecha_Solicitada, Dia_Extra, Motivo) VALUES (?, ?, ?, ?, ?)");
    $queryInsertarPF->bind_param("iisis", $Id_Prestacion, $numeroEmpleado, $fechaConvertida, $diaExtra, $motivo);
    $queryInsertarPF->execute();
    $queryInsertarPF->close();

    // Actualizar días disponibles en 'empleado'
    if ($diaExtra) {
        $queryUpdateED = $conn->prepare("UPDATE empleado SET Dias_Extras = Dias_Extras - 1 WHERE Numero_Empleado = ?");
    } else {
        $queryUpdateED = $conn->prepare("UPDATE empleado SET Dias = Dias - 1 WHERE Numero_Empleado = ?");
    }
    $queryUpdateED->bind_param("i", $numeroEmpleado);
    $queryUpdateED->execute();
    $queryUpdateED->close();

    // Si hay otro motivo, actualizar en 'prestacion_dias'
    if ($otro) {
        $queryActualizarMotivo = $conn->prepare("UPDATE prestacion_dias SET Motivo = ? WHERE Id_Prestacion = ?");
        $queryActualizarMotivo->bind_param("si", $otro, $Id_Prestacion);
        $queryActualizarMotivo->execute();
        $queryActualizarMotivo->close();
    }

    echo json_encode(["success" => true, "message" => "Solicitud enviada correctamente.", "documento" => ($motivo == "Permiso sindical") ? "PermisoSindical.pdf" : ""]);
}
?>
