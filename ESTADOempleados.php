<?php
require_once("conn.php");

// Actualizar estado a 'En descanso' para empleados con prestacion otorgada y fecha solicitada igual a hoy
$queryUpdateDescanso = $conn->prepare("
    UPDATE empleado e
    JOIN prestacion_dias pd ON e.Numero_Empleado = pd.Numero_Empleado
    JOIN prestacion p ON pd.Id_Prestacion = p.Id_Prestacion
    SET e.Estado = 'En descanso'
    WHERE pd.Fecha_Solicitada = CURDATE() AND p.Estado = 'Otorgada'
");
$queryUpdateDescanso->execute();
$queryUpdateDescanso->close();

// Actualizar estado a 'Activo' para empleados que no tienen prestacion otorgada o no tienen fecha solicitada igual a hoy
$queryUpdateActivo = $conn->prepare("
    UPDATE empleado e
    LEFT JOIN prestacion_dias pd ON e.Numero_Empleado = pd.Numero_Empleado AND pd.Fecha_Solicitada = CURDATE()
    LEFT JOIN prestacion p ON pd.Id_Prestacion = p.Id_Prestacion
    SET e.Estado = 'Activo'
    WHERE pd.Numero_Empleado IS NULL OR p.Estado IS NULL
");
$queryUpdateActivo->execute();
$queryUpdateActivo->close();

echo "<script>console.log('Estados de empleados actualizados.');</script>";
?>