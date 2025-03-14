<?php
require_once("conn.php");
include_once("error_handler.php");

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

// Actualizar estado a 'En descanso' para empleados con prestaciones de plazo activas el día de hoy
$queryUpdatePlazo = $conn->prepare("
    UPDATE empleado e
    JOIN prestacion_plazos pp ON e.Numero_Empleado = pp.Numero_Empleado
    JOIN prestacion p ON pp.Id_Prestacion = p.Id_Prestacion
    SET e.Estado = 'En descanso'
    WHERE CURDATE() BETWEEN pp.Fecha_Inicio AND pp.Fecha_Final AND p.Estado = 'Otorgada'
");
$queryUpdatePlazo->execute();
$queryUpdatePlazo->close();


// Actualizar estado a 'Activo' para empleados que no tienen prestaciones otorgadas o no tienen fechas activas hoy
$queryUpdateActivo = $conn->prepare("
    UPDATE empleado e
    LEFT JOIN prestacion_dias pd ON e.Numero_Empleado = pd.Numero_Empleado AND pd.Fecha_Solicitada = CURDATE()
    LEFT JOIN prestacion p1 ON pd.Id_Prestacion = p1.Id_Prestacion AND p1.Estado = 'Otorgada'
    LEFT JOIN prestacion_plazos pp ON e.Numero_Empleado = pp.Numero_Empleado AND CURDATE() BETWEEN pp.Fecha_Inicio AND pp.Fecha_Final
    LEFT JOIN prestacion p2 ON pp.Id_Prestacion = p2.Id_Prestacion AND p2.Estado = 'Otorgada'
    SET e.Estado = 'Activo'
    WHERE (pd.Numero_Empleado IS NULL OR p1.Estado IS NULL)
      AND (pp.Numero_Empleado IS NULL OR p2.Estado IS NULL)
");
$queryUpdateActivo->execute();
$queryUpdateActivo->close();

echo "<script>console.log('Estados de empleados actualizados.');</script>";
?>